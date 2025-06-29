<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'reason',
        'submitted_by',
        'via',
    ];

    protected $casts = [
        'date' => 'date',
        'via' => \App\Enums\LeaveRequestViaEnum::class, // Assuming you will create this Enum
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
