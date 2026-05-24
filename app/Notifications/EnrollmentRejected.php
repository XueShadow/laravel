<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EnrollmentRejected extends Notification
{
    use Queueable;

    public function __construct(public Enrollment $enrollment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message'       => 'Your enrollment for ' . $this->enrollment->academic_year . ' (' . $this->enrollment->semester . ' semester) has been rejected. Notes: ' . ($this->enrollment->notes ?? 'N/A'),
            'enrollment_id' => $this->enrollment->id,
            'type'          => 'enrollment_rejected',
        ];
    }
}
