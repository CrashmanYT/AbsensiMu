<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nis',
        'class_id',
        'gender',
        'fingerprint_id',
        'photo',
        'parent_whatsapp',
    ];

    protected $casts = [
        'gender' => \App\Enums\GenderEnum::class, // Assuming you will create this Enum
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(StudentLeaveRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function disciplineRankings()
    {
        return $this->hasMany(DisciplineRanking::class);
    }
}
