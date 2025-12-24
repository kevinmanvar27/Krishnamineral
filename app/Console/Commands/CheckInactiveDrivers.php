<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Purchase;
use App\Models\Driver;
use App\Models\User;
use App\Notifications\DriverInactiveNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class CheckInactiveDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:check-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for drivers who have been inactive for more than their configured threshold';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Log that the command is running
        \Log::info('CheckInactiveDrivers command started - checking for inactive drivers based on user-configured thresholds');
        
        // Get all pending purchases with drivers
        $pendingPurchases = Purchase::where('status', 0)
            ->with(['driver', 'vehicle'])
            ->get();

        $this->info("Found {$pendingPurchases->count()} pending purchases.");
        \Log::info("Found {$pendingPurchases->count()} pending purchases.");

        $inactiveCount = 0;
        
        foreach ($pendingPurchases as $purchase) {
            // Skip if no driver assigned
            if (!$purchase->driver) {
                $this->info("Purchase #{$purchase->id} has no driver assigned. Skipping.");
                continue;
            }
            
            $this->info("Checking driver {$purchase->driver->name} for purchase #{$purchase->id}");
            $this->info("Driver active status: " . ($purchase->driver->is_active ? 'Yes' : 'No'));
            $this->info("Driver last active: " . ($purchase->driver->last_active_at ? $purchase->driver->last_active_at : 'Never'));
            
            // Check if driver is inactive for more than the configured threshold
            $isInactive = $purchase->driver->isInactiveForMoreThanThreshold();
            $this->info("Driver is inactive for configured threshold: " . ($isInactive ? 'Yes' : 'No'));
            
            if ($isInactive) {
                // Check if driver is linked to a user and if that user has attendance marked as present for today
                $shouldSendNotification = true;
                if ($purchase->driver->user_id) {
                    $user = User::find($purchase->driver->user_id);
                    if ($user) {
                        // Check if user has attendance marked as present for today
                        $todayAttendance = \App\Models\Attendance::where('employee_id', $user->id)
                                               ->whereDate('date', now()->toDateString())
                                               ->where('type_attendance', 1)  // Only consider 'present' status
                                               ->first();
                                            
                        // Only proceed if user has attendance marked as present today
                        if (!$todayAttendance) {
                            $this->info("Driver {$purchase->driver->name} is linked to user {$user->name} who does not have attendance marked as present for today. Skipping notification.");
                            $shouldSendNotification = false;
                        }
                    }
                }
                            
                if ($shouldSendNotification) {
                    // Get the threshold for this driver
                    $threshold = 1;
                    if ($purchase->driver->user_id) {
                        $user = User::find($purchase->driver->user_id);
                        if ($user && $user->work_timing_initiate_checking !== null) {
                            $threshold = $user->work_timing_initiate_checking;
                        }
                    }
                                    
                    // Check if a similar notification was already sent recently
                    $existingNotification = $this->checkExistingNotification($purchase->driver->id, $purchase->id, $threshold);
                                    
                    if (!$existingNotification) {
                        // Send notification to super admins
                        // $superAdmins = User::role('super-admin')->get();
                        $superAdmins = User::where('user_type', 1)->get();
                        $this->info("Found {$superAdmins->count()} super admins.");
                                    
                        if ($superAdmins->count() > 0) {
                            try {
                                // Get the threshold for this driver
                                $threshold = 1;
                                if ($purchase->driver->user_id) {
                                    $user = User::find($purchase->driver->user_id);
                                    if ($user && $user->work_timing_initiate_checking !== null) {
                                        $threshold = $user->work_timing_initiate_checking;
                                    }
                                }
                                            
                                Notification::send($superAdmins, new DriverInactiveNotification($purchase, $purchase->driver, $threshold));
                                $inactiveCount++;
                                            
                                // Log the notification
                                $this->info("Notification sent for driver: {$purchase->driver->name} (Purchase #{$purchase->id})");
                            } catch (\Exception $e) {
                                $this->error("Failed to send notification: " . $e->getMessage());
                            }
                        } else {
                            $this->info("No super admins found. Notification not sent.");
                        }
                    } else {
                        $this->info("Notification already sent for driver: {$purchase->driver->name} (Purchase #{$purchase->id}). Skipping.");
                    }
                }
            } else {
                $this->info("Driver is still active. No notification needed.");
            }
        }
        
        // Also check for users with driver role assigned to purchases
        $this->checkUserDrivers($pendingPurchases, $inactiveCount);
        
        $this->info("Checked {$pendingPurchases->count()} pending purchases. Sent notifications for {$inactiveCount} inactive drivers.");
        
        return 0;
    }
    
    /**
     * Check for drivers that are linked to users with driver role
     * 
     * @param \Illuminate\Database\Eloquent\Collection $pendingPurchases
     * @param int $inactiveCount
     * @return void
     */
    private function checkUserDrivers($pendingPurchases, &$inactiveCount)
    {
        // Get drivers that are linked to users
        $userLinkedDrivers = Driver::whereNotNull('user_id')->get();
        
        $this->info("Found {$userLinkedDrivers->count()} drivers linked to users.");
        
        foreach ($userLinkedDrivers as $driver) {
            // Check if this driver is assigned to any pending purchases
            $assignedPurchases = Purchase::where('status', 0)
                ->where('driver_id', $driver->id)
                ->get();
                
            foreach ($assignedPurchases as $purchase) {
                $this->info("Checking user-linked driver {$driver->name} for purchase #{$purchase->id}");
                $this->info("Driver active status: " . ($driver->is_active ? 'Yes' : 'No'));
                $this->info("Driver last active: " . ($driver->last_active_at ? $driver->last_active_at : 'Never'));
                
                // Check if driver is inactive for more than the configured threshold
                $isInactive = $driver->isInactiveForMoreThanThreshold();
                $this->info("Driver is inactive for more than configured threshold: " . ($isInactive ? 'Yes' : 'No'));
                
                if ($isInactive) {
                    // Check if driver is linked to a user and if that user has attendance marked as present for today
                    $shouldSendNotification = true;
                    if ($driver->user_id) {
                        $user = User::find($driver->user_id);
                        if ($user) {
                            // Check if user has attendance marked as present for today
                            $todayAttendance = \App\Models\Attendance::where('employee_id', $user->id)
                                                   ->whereDate('date', now()->toDateString())
                                                   ->where('type_attendance', 1)  // Only consider 'present' status
                                                   ->first();
                                                    
                            // Only proceed if user has attendance marked as present today
                            if (!$todayAttendance) {
                                $this->info("User-linked driver {$driver->name} is linked to user {$user->name} who does not have attendance marked as present for today. Skipping notification.");
                                $shouldSendNotification = false;
                            }
                        }
                    }
                                    
                    if ($shouldSendNotification) {
                        // Get the threshold for this driver
                        $threshold = 1;
                        if ($driver->user_id) {
                            $user = User::find($driver->user_id);
                            if ($user && $user->work_timing_initiate_checking !== null) {
                                $threshold = $user->work_timing_initiate_checking;
                            }
                        }
                                            
                        // Check if a similar notification was already sent recently
                        $existingNotification = $this->checkExistingNotification($driver->id, $purchase->id, $threshold);
                                            
                        if (!$existingNotification) {
                            // Send notification to super admins
                            $superAdmins = User::role('super-admin')->get();
                            $this->info("Found {$superAdmins->count()} super admins.");
                                            
                            if ($superAdmins->count() > 0) {
                                try {
                                    // Get the threshold for this driver
                                    $threshold = 1;
                                    if ($driver->user_id) {
                                        $user = User::find($driver->user_id);
                                        if ($user && $user->work_timing_initiate_checking !== null) {
                                            $threshold = $user->work_timing_initiate_checking;
                                        }
                                    }
                                                    
                                    Notification::send($superAdmins, new DriverInactiveNotification($purchase, $driver, $threshold));
                                    $inactiveCount++;
                                                    
                                    // Log the notification
                                    $this->info("Notification sent for user-linked driver: {$driver->name} (Purchase #{$purchase->id})");
                                } catch (\Exception $e) {
                                    $this->error("Failed to send notification: " . $e->getMessage());
                                }
                            } else {
                                $this->info("No super admins found. Notification not sent.");
                            }
                        } else {
                            $this->info("Notification already sent for user-linked driver: {$driver->name} (Purchase #{$purchase->id}). Skipping.");
                        }
                    }
                } else {
                    $this->info("User-linked driver is still active. No notification needed.");
                }
            }
        }
    }

    /**
     * Check if a similar notification was already sent in the last 5 minutes
     * to prevent duplicate notifications
     */
    private function checkExistingNotification($driverId, $purchaseId, $threshold = null)
    {
        // Check if a notification for this specific driver and purchase was sent in the last 5 minutes
        $recentTime = now()->subMinutes(5);
            
        // Get all super admins and check their notifications
        $superAdmins = User::role('super-admin')->get();
            
        foreach ($superAdmins as $admin) {
            $query = $admin->notifications()
                ->where('created_at', '>', $recentTime)
                ->where('type', DriverInactiveNotification::class)
                ->whereJsonContains('data->driver_id', $driverId)
                ->whereJsonContains('data->purchase_id', $purchaseId);
                    
            // If threshold is provided, also check for the threshold to avoid duplicates for the same threshold
            if ($threshold !== null) {
                $query = $query->whereJsonContains('data->threshold', $threshold);
            }
                
            $existingNotification = $query->first();
                
            if ($existingNotification) {
                return true;
            }
        }
            
        return false;
    }
}