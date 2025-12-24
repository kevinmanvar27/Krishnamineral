<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckWorkTimingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work-timing:check-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check work timing and send notifications to users based on their work timing settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentTime = now();
        $this->info("Work timing notification check started at: " . $currentTime->format('Y-m-d H:i:s'));
        
        // Get all users with work timing enabled
        // Only check users who are currently logged in (have an active session_id)
        // And exclude those who have already completed their task
        $users = \App\Models\User::where('work_timing_enabled', true)
                     ->whereNotNull('work_timing_initiate_checking')
                     ->where('task_completed', false)
                     ->whereNotNull('task_start_time')
                     ->whereNotNull('session_id')  // Only users who are currently logged in
                     ->get();
        
        $this->info("Found {$users->count()} users to check.");
        
        $notifiedCount = 0;
        
        foreach ($users as $user) {
            // Check if the task has been running for the specified minutes
            $minutesSinceStart = $user->task_start_time->diffInMinutes($currentTime);
            $requiredMinutes = $user->work_timing_initiate_checking;
            
            $this->info("Checking user: {$user->username}, Task started: {$user->task_start_time}, Minutes since start: {$minutesSinceStart}, Required minutes: {$requiredMinutes}");
            
            // Check if the required minutes have passed since the task started
            if ($minutesSinceStart >= $requiredMinutes) {
                
                $this->info("Threshold reached for user: {$user->username} ({$minutesSinceStart} >= {$requiredMinutes})");
                
                // Check if a notification was already sent for this specific task in the last 15 minutes
                // to prevent duplicate notifications
                $existingNotification = $this->checkExistingNotification($user->id, $user->task_description, $requiredMinutes);
                
                if (!$existingNotification) {
                    $user->notify(new \App\Notifications\WorkTimingNotification($requiredMinutes, $user->task_description));
                    
                    $this->info("Notification sent to user: {$user->username} (Task running for {$minutesSinceStart} minutes, threshold: {$requiredMinutes} minutes)");
                    $notifiedCount++;
                } else {
                    $this->info("Notification already sent for user: {$user->username} for this task in the last 15 minutes. Skipping.");
                }
            } else {
                $this->info("Threshold not reached for user: {$user->username} ({$minutesSinceStart} < {$requiredMinutes}). No notification needed.");
            }
        }
        
        if ($notifiedCount === 0) {
            $this->info('No users found with tasks that have exceeded their time threshold.');
        } else {
            $this->info("Work timing notifications processed successfully. Notified {$notifiedCount} users.");
        }
        
        $this->info("Work timing notification check completed at: " . now()->format('Y-m-d H:i:s'));
        
        return 0;
    }
    
    /**
     * Check if a notification was already sent for this specific task
     */
    private function checkExistingNotification($userId, $taskDescription, $requiredMinutes)
    {
        // Check if a WorkTimingNotification was sent in the last 15 minutes for this user
        $recentTime = now()->subMinutes(15);
        
        // Get the user and check their notifications
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return false;
        }
        
        // Look for recent WorkTimingNotification for this user with the same threshold
        $existingNotification = $user->notifications()
            ->where('created_at', '>', $recentTime)
            ->where('type', \App\Notifications\WorkTimingNotification::class)
            ->whereJsonContains('data->user_id', $userId)
            ->whereJsonContains('data->minutes_threshold', $requiredMinutes)
            ->first();
            
        return $existingNotification !== null;
    }
}
