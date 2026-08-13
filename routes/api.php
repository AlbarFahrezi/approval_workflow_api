<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApprovalRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/profile', [AuthController::class, 'updateProfile']);

    Route::put(
        '/profile/password',
        [AuthController::class, 'changePassword']
    );

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );

    /*
    |--------------------------------------------------------------------------
    | Approval Request CRUD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/approval-requests',
        [ApprovalRequestController::class, 'index']
    );

    Route::post(
        '/approval-requests',
        [ApprovalRequestController::class, 'store']
    );

    Route::get(
        '/approval-requests/{approvalRequest}',
        [ApprovalRequestController::class, 'show']
    );

    Route::put(
        '/approval-requests/{approvalRequest}',
        [ApprovalRequestController::class, 'update']
    );

    Route::delete(
        '/approval-requests/{approvalRequest}',
        [ApprovalRequestController::class, 'destroy']
    );

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
    | Approval History
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/approval-requests/{approvalRequest}/history',
        [ApprovalController::class, 'history']
    );

    /*
    |--------------------------------------------------------------------------
    | Approval Timeline
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/approval-requests/{approvalRequest}/timeline',
        [ApprovalController::class, 'timeline']
    );

    /*
    |--------------------------------------------------------------------------
    | Manager Workflow
    |--------------------------------------------------------------------------
    */

    Route::middleware('manager')->group(function () {

        // Submitted -> Approved
        Route::post(
            '/approval-requests/{approvalRequest}/approve',
            [ApprovalController::class, 'approve']
        );

        // Submitted -> Rejected
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

        Route::get(
            '/users',
            [UserController::class, 'index']
        );

        Route::post(
            '/users',
            [UserController::class, 'store']
        );

        Route::get(
            '/users/{user}',
            [UserController::class, 'show']
        );

        Route::put(
            '/users/{user}',
            [UserController::class, 'update']
        );

        Route::delete(
            '/users/{user}',
            [UserController::class, 'destroy']
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    // Semua notifikasi user yang sedang login
    Route::get('/notifications', function (Request $request) {

        return response()->json([
            'success' => true,
            'data' => $request->user()
                ->notifications()
                ->latest()
                ->get(),
        ]);
    });

    // Notifikasi yang belum dibaca
    Route::get('/notifications/unread', function (Request $request) {

        return response()->json([
            'success' => true,
            'data' => $request->user()
                ->unreadNotifications()
                ->latest()
                ->get(),
        ]);
    });

    // Tandai satu notifikasi sebagai dibaca
    Route::post('/notifications/{id}/read', function (
        Request $request,
        string $id
    ) {

        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibaca.',
        ]);
    });

    // Tandai semua notifikasi sebagai dibaca
    Route::post('/notifications/read-all', function (
        Request $request
    ) {

        $request->user()
            ->unreadNotifications()
            ->get()
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dibaca.',
        ]);
    });
});