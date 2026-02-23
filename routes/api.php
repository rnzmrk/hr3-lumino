<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\attendanceController;
use App\Http\Controllers\Api\ShiftController;

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

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Add protected routes here later
});
