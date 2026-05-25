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
        $logData = [];
        $batchSize = 500;

        $this->command->info('Generating activity logs for 5000 users...');

        foreach ($users as $index => $user) {
            // Create 8-20 activity logs per user
            $numLogs = rand(8, 20);

            for ($i = 0; $i < $numLogs; $i++) {
                $activity = $activities[array_rand($activities)];
                $description = $descriptions[$activity] . ' - ' . date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days'));

                $logData[] = [
                    'user_id' => $user->id,
                    'action' => $activity,
                    'description' => $description,
                    'ip_address' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                    'created_at' => now()->subDays(rand(1, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                    'updated_at' => now(),
                ];

                if (count($logData) >= $batchSize) {
                    ActivityLog::insert($logData);
                    $logCount += count($logData);
                    $logData = [];
                }
            }

            if ($index % 500 === 0 && $index > 0) {
                $this->command->info("Processed $index users...");
            }
        }

        if (!empty($logData)) {
            ActivityLog::insert($logData);
            $logCount += count($logData);
        }

        $this->command->info($logCount . ' dummy activity logs created successfully!');
    }
}
