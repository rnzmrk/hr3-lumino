<?php

namespace App\Http\Controllers\Api;

use App\Models\Shift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(){
        return response()->json(Shift::all());
    }
}
