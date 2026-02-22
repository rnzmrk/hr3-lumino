<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    protected $fillable = [
        'employee_id',
        'employee_name',
        'date',
        'check_in',
        'check_out',
        'overtime',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
        'overtime' => 'datetime:H:i:s',
    ];
}
