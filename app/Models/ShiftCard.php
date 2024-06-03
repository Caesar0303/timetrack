<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftCard extends Model
{
    protected $fillable = [
        'day',
        'employee_id',
        'admin_id',
        'start_time',
        'start_of_shift_time',
        'end_time',
        'end_of_shift_time',
        'status',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];


    use HasFactory;
}
