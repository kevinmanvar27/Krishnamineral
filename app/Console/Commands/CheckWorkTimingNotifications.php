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
        
        // Get all users with work timing enabled
        // Only check users who are currently logged in (have an active session_id)
        $users = \App\Models\User::where('work_timing_enabled', true)
                     ->whereNotNull('work_timing_initiate_checking')
                     ->where('task_completed', false)
                     ->whereNotNull('task_start_time')
                     ->whereNotNull('session_id')  // Only users who are currently logged in
                     ->get();
        
        $notifiedCount = 0;
        
        foreach ($users as $user) {
            // Check if the task has been running for the specified minutes
            $minutesSinceStart = $user->task_start_time->diffInMinutes($currentTime);
            $requiredMinutes = $user->work_timing_initiate_checking;
            
            // Check if the required minutes have passed since the task started
            if ($minutesSinceStart >= $requiredMinutes) {
                
                $user->notify(new \App\Notifications\WorkTimingNotification($requiredMinutes, $user->task_description));
                
                $this->info("Notification sent to user: {$user->username} (Task running for {$minutesSinceStart} minutes, threshold: {$requiredMinutes} minutes)");
                $notifiedCount++;
            }
        }
        
        if ($notifiedCount === 0) {
            $this->info('No users found with tasks that have exceeded their time threshold.');
        } else {
            $this->info("Work timing notifications processed successfully. Notified {$notifiedCount} users.");
        }
        
        return 0;
    }
}
