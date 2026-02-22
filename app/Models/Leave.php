<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'leave_name',
        'leave_description',
        'leave_duration'
    ];
}
