<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\attendanceController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\LeaveRequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/attendance-record', [attendanceController::class, 'index']);
Route::get('/shift-record', [ShiftController::class, 'index']);
Route::get('/claim-record', [ClaimController::class, 'index']);
Route::get('/leave-record', [LeaveRequestController::class, 'index']);


// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Add protected routes here later
});
