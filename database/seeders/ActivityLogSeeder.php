<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users
        $users = User::take(3)->get();
        
        // Create sample activity logs
        foreach ($users as $user) {
            Activity::create([
                'log_name' => 'default',
                'description' => 'User logged in',
                'subject_type' => 'App\Models\User',
                'subject_id' => $user->id,
                'causer_type' => 'App\Models\User',
                'causer_id' => $user->id,
                'properties' => json_encode(['ip' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0']),
                'event' => 'login',
            ]);
            
            Activity::create([
                'log_name' => 'default',
                'description' => 'User updated profile',
                'subject_type' => 'App\Models\User',
                'subject_id' => $user->id,
                'causer_type' => 'App\Models\User',
                'causer_id' => $user->id,
                'properties' => json_encode(['name' => $user->name, 'email' => $user->email]),
                'event' => 'updated',
            ]);
        }
    }
}