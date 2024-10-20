<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'visit_type_id',
        'arrival_datetime',
        'exit_datetime',
        'pick_up',
        'drop_off',
        'pick_up_time',
        'drop_off_time',
        'pick_up_staff',
        'drop_off_staff',
        'pick_up_bus',
        'drop_off_bus',
        'notes'
    ];
}
