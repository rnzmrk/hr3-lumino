<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class attendanceController extends Controller
{
    public function index(){
        return response()->json(Attendance::all());
    }
}
