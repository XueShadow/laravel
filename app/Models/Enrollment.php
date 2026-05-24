<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING   = 'pending';
    const APPROVED  = 'approved';
    const REJECTED  = 'rejected';
    const COMPLETED = 'completed';

    protected $fillable = [
        'student_id', 'academic_year', 'semester', 'status',
        'approved_by', 'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'enrollment_subjects');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canApprove(): bool
    {
        return $this->status === self::PENDING;
    }

    public function canReject(): bool
    {
        return $this->status === self::PENDING;
    }
}
