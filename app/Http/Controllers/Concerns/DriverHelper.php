<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Driver;
use App\Models\User;
use Spatie\Permission\Models\Role;

trait DriverHelper
{
    /**
     * Get combined list of drivers from drivers table and users table
     * Users with 'driver' role are considered drivers
     * 
     * @param string $tableType
     * @return \Illuminate\Support\Collection
     */
    protected function getCombinedDrivers($tableType = 'purchase')
    {
        // Get drivers from drivers table
        $drivers = Driver::where('table_type', $tableType)->get();
        
        // Get users with 'driver' role by name
        $driverRole = Role::where('name', 'driver')->first();
        $users = collect();
        if ($driverRole) {
            $users = User::where('user_type', $driverRole->id)->get();
        }
        
        // Combine both collections
        $combinedDrivers = collect();
        
        // Add drivers from drivers table
        foreach ($drivers as $driver) {
            // If this driver entry is linked to a user, modify the name to indicate it
            $displayName = $driver->name;
            if ($driver->user_id) {
                $displayName .= " - Krishna's Employee";
            }
            
            $combinedDrivers->push([
                'id' => 'driver_' . $driver->id,
                'name' => $displayName,
                'type' => 'driver',
                'original_id' => $driver->id,
                'contact_number' => $driver->contact_number
            ]);
        }
        
        // Add users with driver designation
        // But skip users who already have an entry in the drivers table
        $userIdsWithDriverEntries = Driver::whereNotNull('user_id')->pluck('user_id')->toArray();
        
        foreach ($users as $user) {
            // Skip users who already have a driver entry
            if (in_array($user->id, $userIdsWithDriverEntries)) {
                continue;
            }
            
            $combinedDrivers->push([
                'id' => 'user_' . $user->id,
                'name' => $user->name . " - Krishna's Employee",
                'type' => 'user',
                'original_id' => $user->id,
                'contact_number' => $user->contact_number
            ]);
        }
        
        return $combinedDrivers;
    }
    
    /**
     * Get driver by combined ID
     * 
     * @param string $combinedId
     * @return mixed
     */
    protected function getDriverByCombinedId($combinedId)
    {
        if (strpos($combinedId, 'driver_') === 0) {
            $id = str_replace('driver_', '', $combinedId);
            return Driver::find($id);
        } elseif (strpos($combinedId, 'user_') === 0) {
            $id = str_replace('user_', '', $combinedId);
            return User::find($id);
        }
        
        return null;
    }
}