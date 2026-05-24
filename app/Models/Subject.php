<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'units', 'schedule', 'description', 'is_active'];

    protected $casts = ['schedule' => 'array', 'is_active' => 'boolean', 'units' => 'integer'];

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_subjects');
    }

    public static function checkConflicts(array $subjectIds): array
    {
        $subjects = self::whereIn('id', $subjectIds)->get();
        $conflicts = [];
        for ($i = 0; $i < count($subjects); $i++) {
            for ($j = $i + 1; $j < count($subjects); $j++) {
                $a = $subjects[$i];
                $b = $subjects[$j];
                foreach ($a->schedule as $slotA) {
                    foreach ($b->schedule as $slotB) {
                        if ($slotA['day'] === $slotB['day']) {
                            $aStart = strtotime($slotA['start_time']);
                            $aEnd   = strtotime($slotA['end_time']);
                            $bStart = strtotime($slotB['start_time']);
                            $bEnd   = strtotime($slotB['end_time']);
                            if ($aStart < $bEnd && $aEnd > $bStart) {
                                $conflicts[] = "Conflict between {$a->code} and {$b->code} on {$slotA['day']}";
                            }
                        }
                    }
                }
            }
        }
        return $conflicts;
    }
}
