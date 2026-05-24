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
        
        foreach ($users as $user) {
            // Create 0-3 OTP records per user
            $numOtps = rand(0, 3);
            
            for ($i = 0; $i < $numOtps; $i++) {
                $used = rand(0, 1) === 1;
                $expiresAt = now()->subDays(rand(1, 30))->subHours(rand(1, 23))->subMinutes(rand(1, 59));
                
                Otp::create([
                    'user_id' => $user->id,
                    'code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                    'expires_at' => $expiresAt,
                    'used' => $used,
                    'created_at' => $expiresAt->copy()->subMinutes(rand(5, 60)),
                ]);
                
                $otpCount++;
            }
        }
        
        $this->command->info($otpCount . ' dummy OTP records created successfully!');
    }
}
