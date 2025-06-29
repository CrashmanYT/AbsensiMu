<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'date',
        'reason',
        'submitted_by',
        'via',
    ];

    protected $casts = [
        'date' => 'date',
        'via' => \App\Enums\LeaveRequestViaEnum::class, // Assuming you will create this Enum
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
