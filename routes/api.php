<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ApprovalRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Approval Request CRUD
    Route::get('/approval-requests', [ApprovalRequestController::class, 'index']);
    Route::post('/approval-requests', [ApprovalRequestController::class, 'store']);
    Route::get('/approval-requests/{approvalRequest}', [ApprovalRequestController::class, 'show']);
    Route::put('/approval-requests/{approvalRequest}', [ApprovalRequestController::class, 'update']);
    Route::delete('/approval-requests/{approvalRequest}', [ApprovalRequestController::class, 'destroy']);
});