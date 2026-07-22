<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApprovalRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Approval Request CRUD
    |--------------------------------------------------------------------------
    */

    Route::get('/approval-requests', [ApprovalRequestController::class, 'index']);
    Route::post('/approval-requests', [ApprovalRequestController::class, 'store']);
    Route::get('/approval-requests/{approvalRequest}', [ApprovalRequestController::class, 'show']);
    Route::put('/approval-requests/{approvalRequest}', [ApprovalRequestController::class, 'update']);
    Route::delete('/approval-requests/{approvalRequest}', [ApprovalRequestController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Employee Workflow
    |--------------------------------------------------------------------------
    */

    // Draft -> Submitted
    Route::post(
        '/approval-requests/{approvalRequest}/submit',
        [ApprovalController::class, 'submit']
    );

    /*
    |--------------------------------------------------------------------------
    | History Workflow
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/approval-requests/{approvalRequest}/history',
        [ApprovalController::class, 'history']
    );

    /*
    |--------------------------------------------------------------------------
    | Manager Workflow
    |--------------------------------------------------------------------------
    */

    Route::middleware('manager')->group(function () {

        Route::post(
            '/approval-requests/{approvalRequest}/approve',
            [ApprovalController::class, 'approve']
        );

        Route::post(
            '/approval-requests/{approvalRequest}/reject',
            [ApprovalController::class, 'reject']
        );

    });

    /*
    |--------------------------------------------------------------------------
    | Admin User Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {

        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

    });

});