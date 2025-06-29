<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'fingerprint_id',
        'event_type',
        'scanned_at',
        'device_id',
        'result',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'event_type' => \App\Enums\EventTypeEnum::class, // Assuming you will create this Enum
        'result' => \App\Enums\ScanResultEnum::class, // Assuming you will create this Enum
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
