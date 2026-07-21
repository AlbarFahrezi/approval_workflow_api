<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Dashboard Summary
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard berhasil diambil.',
            'data' => [

                'total_requests' => ApprovalRequest::count(),

                'draft' => ApprovalRequest::where('status', 'draft')->count(),

                'submitted' => ApprovalRequest::where('status', 'submitted')->count(),

                'approved' => ApprovalRequest::where('status', 'approved')->count(),

                'rejected' => ApprovalRequest::where('status', 'rejected')->count(),

                'total_users' => User::count(),

                'total_managers' => User::where('role', 'manager')->count(),

                'total_employees' => User::where('role', 'employee')->count(),

            ]
        ]);
    }
}