<?php

namespace Database\Seeders;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyOtpSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $otpCount = 0;
        $otpData = [];
        $batchSize = 500;

        $this->command->info('Generating OTP records for 5000 users...');

        foreach ($users as $index => $user) {
            // Create 0-5 OTP records per user
            $numOtps = rand(0, 5);

            for ($i = 0; $i < $numOtps; $i++) {
                $used = rand(0, 1) === 1;
                $expiresAt = now()->subDays(rand(1, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                $otpData[] = [
                    'user_id' => $user->id,
                    'code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                    'expires_at' => $expiresAt,
                    'used' => $used,
                    'created_at' => $expiresAt->copy()->subMinutes(rand(5, 60)),
                    'updated_at' => now(),
                ];

                if (count($otpData) >= $batchSize) {
                    Otp::insert($otpData);
                    $otpCount += count($otpData);
                    $otpData = [];
                }
            }

            if ($index % 500 === 0 && $index > 0) {
                $this->command->info("Processed $index users...");
            }
        }

        if (!empty($otpData)) {
            Otp::insert($otpData);
            $otpCount += count($otpData);
        }

        $this->command->info($otpCount . ' dummy OTP records created successfully!');
    }
}
