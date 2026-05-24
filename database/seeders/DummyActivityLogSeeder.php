<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $activities = ['login', 'logout', 'profile_update', 'enrollment_submitted', 'password_reset', 'register'];
        $descriptions = [
            'login' => 'User logged in from IP address',
            'logout' => 'User logged out successfully',
            'profile_update' => 'Student updated their profile information',
            'enrollment_submitted' => 'Student submitted enrollment for review',
            'password_reset' => 'User reset their password',
            'register' => 'New user registered in the system',
        ];

        $logCount = 0;

        foreach ($users as $user) {
            // Create 5-15 activity logs per user
            $numLogs = rand(5, 15);

            for ($i = 0; $i < $numLogs; $i++) {
                $activity = $activities[array_rand($activities)];
                $description = $descriptions[$activity] . ' - ' . date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'));

                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => $activity,
                    'description' => $description,
                    'ip_address' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                    'created_at' => now()->subDays(rand(1, 30))->subHours(rand(1, 23))->subMinutes(rand(1, 59)),
                ]);

                $logCount++;
            }
        }

        $this->command->info($logCount . ' dummy activity logs created successfully!');
    }
}
