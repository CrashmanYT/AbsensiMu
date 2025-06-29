<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',
        'recipient',
        'content',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'type' => \App\Enums\NotificationTypeEnum::class, // Assuming you will create this Enum
        'recipient' => \App\Enums\NotificationRecipientEnum::class, // Assuming you will create this Enum
        'status' => \App\Enums\NotificationStatusEnum::class, // Assuming you will create this Enum
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
