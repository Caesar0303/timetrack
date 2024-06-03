<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'employee_id',
        'start_time',
        'start_of_shift_time',
        'end_time',
        'end_of_shift_time',
        'status',
    ];
    use HasFactory;
}
