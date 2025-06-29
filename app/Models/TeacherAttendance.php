<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'photo_in',
        'device_id',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'status' => \App\Enums\AttendanceStatusEnum::class, // Assuming you will create this Enum
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
