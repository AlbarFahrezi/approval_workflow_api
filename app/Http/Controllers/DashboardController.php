<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\User;


/**
 * @OA\Tag(
 *     name="Dashboard",
 *     description="Dashboard Summary API"
 * )
 */
class DashboardController extends Controller
{
    /**
     * Dashboard Summary
     */

    /**
 * @OA\Get(
 *     path="/api/dashboard",
 *     tags={"Dashboard"},
 *     summary="Dashboard Summary",
 *     description="Menampilkan ringkasan jumlah request berdasarkan status dan jumlah user.",
 *     security={{"sanctum":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Dashboard berhasil diambil"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function index()
    {
        $totalRequests = ApprovalRequest::count();

        $draft = ApprovalRequest::where('status', 'draft')->count();
        $submitted = ApprovalRequest::where('status', 'submitted')->count();
        $approved = ApprovalRequest::where('status', 'approved')->count();
        $rejected = ApprovalRequest::where('status', 'rejected')->count();

        $requestsToday = ApprovalRequest::whereDate('created_at', today())->count();

        $approvedToday = ApprovalRequest::whereDate('approved_at', today())->count();

        $rejectedToday = ApprovalRequest::whereDate('rejected_at', today())->count();

        $approvalRate = $totalRequests > 0
            ? round(($approved / $totalRequests) * 100, 2)
            : 0;

        return response()->json([
            'success' => true,
            'message' => 'Dashboard berhasil diambil.',
            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Request Summary
                |--------------------------------------------------------------------------
                */

                'total_requests' => $totalRequests,

                'draft' => $draft,
                'submitted' => $submitted,
                'approved' => $approved,
                'rejected' => $rejected,

                /*
                |--------------------------------------------------------------------------
                | Today Summary
                |--------------------------------------------------------------------------
                */

                'requests_today' => $requestsToday,
                'approved_today' => $approvedToday,
                'rejected_today' => $rejectedToday,

                /*
                |--------------------------------------------------------------------------
                | Statistics
                |--------------------------------------------------------------------------
                */

                'approval_rate' => $approvalRate . '%',

                /*
                |--------------------------------------------------------------------------
                | User Summary
                |--------------------------------------------------------------------------
                */

                'total_users' => User::count(),
                'total_managers' => User::where('role', 'manager')->count(),
                'total_employees' => User::where('role', 'employee')->count(),
                'total_admins' => User::where('role', 'admin')->count(),
            ]
        ]);
    }
}