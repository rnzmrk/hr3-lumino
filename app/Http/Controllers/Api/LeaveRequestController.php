<?php

namespace App\Http\Controllers\Api;

use App\Models\LeaveRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(){
        return response()->json(LeaveRequest::all());
    }
}
