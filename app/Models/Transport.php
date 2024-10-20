<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $fillable = [
        'people_id',
        'pickup_time',
        'pickup_completed',
        'dropoff_time',
        'dropoff_completed',
    ];

    // ScheduledVisit モデルとのリレーション
    public function scheduledVisit()
    {
        return $this->belongsTo(ScheduledVisit::class, 'scheduled_visit_id');
    }
}

