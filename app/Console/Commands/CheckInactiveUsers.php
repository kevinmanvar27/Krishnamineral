<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\UserInactiveNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class CheckInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for users who have been inactive for more than their configured threshold';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Log that the command is running
        \Log::info('CheckInactiveUsers command started - checking for inactive users based on user-configured thresholds');
        
        $this->info('Starting check for inactive users...');
        
        // Get all users with work timing enabled and a threshold set
        $users = User::where('work_timing_enabled', true)
                     ->whereNotNull('work_timing_initiate_checking')
                     ->where('work_timing_initiate_checking', '>', 0)
                     ->get();
        
        $this->info("Found {$users->count()} users with work timing enabled.");
        
        $inactiveCount = 0;
        
        foreach ($users as $user) {
            $this->info("Checking user: {$user->name} (Threshold: {$user->work_timing_initiate_checking} minutes)");
            
            // Check if user has been inactive beyond their threshold
            // This includes users who have never had any activity recorded
            $isInactive = false;
            $inactiveMinutes = 0;
            
            if (!$user->last_activity_at) {
                // User has never had any activity recorded since we started tracking
                // Consider them inactive from the time they were created or from a reasonable default
                // For simplicity, we'll consider them inactive since account creation or a default time
                $inactiveMinutes = $user->created_at->diffInMinutes(now());
                $isInactive = $inactiveMinutes > $user->work_timing_initiate_checking;
            } else {
                // User has activity recorded, check against last activity
                $inactiveMinutes = $user->last_activity_at->diffInMinutes(now());
                $isInactive = $inactiveMinutes > $user->work_timing_initiate_checking;
            }
            
            if ($isInactive) {
                $this->info("User {$user->name} has been inactive for {$inactiveMinutes} minutes (threshold: {$user->work_timing_initiate_checking} minutes)");
                
                // Check if a similar notification was already sent recently
                $existingNotification = $this->checkExistingNotification($user->id);
                
                if (!$existingNotification) {
                    // Send notification to super admins
                    $superAdmins = User::role('super-admin')->get();
                    $this->info("Found {$superAdmins->count()} super admins.");
                    
                    if ($superAdmins->count() > 0) {
                        try {
                            Notification::send($superAdmins, new UserInactiveNotification($user, $user->work_timing_initiate_checking));
                            $inactiveCount++;
                            
                            // Log the notification
                            $this->info("Notification sent for user: {$user->name} (Inactive for {$inactiveMinutes} minutes)");
                        } catch (\Exception $e) {
                            $this->error("Failed to send notification: " . $e->getMessage());
                        }
                    } else {
                        $this->info("No super admins found. Notification not sent.");
                    }
                } else {
                    $this->info("Notification already sent for user: {$user->name}. Skipping.");
                }
            } else {
                $this->info("User {$user->name} is active (inactive for {$inactiveMinutes} minutes, threshold: {$user->work_timing_initiate_checking} minutes). No notification needed.");
            }
        }
        
        $this->info("Checked {$users->count()} users. Sent notifications for {$inactiveCount} inactive users.");
        
        return 0;
    }
    
    /**
     * Check if a similar notification was already sent in the last 5 minutes
     * to prevent duplicate notifications
     */
    private function checkExistingNotification($userId)
    {
        // Check if a notification for this specific user was sent in the last 5 minutes
        $recentTime = now()->subMinutes(5);
        
        // Get all super admins and check their notifications
        $superAdmins = User::role('super-admin')->get();
        
        foreach ($superAdmins as $admin) {
            $existingNotification = $admin->notifications()
                ->where('created_at', '>', $recentTime)
                ->where('type', UserInactiveNotification::class)
                ->whereJsonContains('data->user_id', $userId)
                ->first();
                
            if ($existingNotification) {
                return true;
            }
        }
        
        return false;
    }
}
