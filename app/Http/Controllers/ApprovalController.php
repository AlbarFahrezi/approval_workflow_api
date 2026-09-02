<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovalActionRequest;
use App\Models\ApprovalHistory;
use App\Models\ApprovalRequest;
use App\Models\User;
use App\Notifications\ApprovalRequestNotification;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Approval Workflow",
 *     description="Approval Workflow API"
 * )
 *
 * @OA\Tag(
 *     name="Approval History",
 *     description="Approval History & Timeline API"
 * )
 */
class ApprovalController extends Controller
{
    /**
     * Submit Request
     *
     * Draft -> Submitted
     */
    public function submit(ApprovalRequest $approvalRequest)
    {
        /*
        |--------------------------------------------------------------------------
        | Hanya request milik user yang boleh disubmit
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk submit request ini.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya Draft yang boleh disubmit
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->status !== 'draft') {
            return response()->json([
                'message' => 'Request sudah diproses dan tidak bisa disubmit lagi.'
            ], 400);
        }

        $oldStatus = $approvalRequest->status;

        $approvalRequest->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Approval History
        |--------------------------------------------------------------------------
        */

        ApprovalHistory::create([
            'approval_request_id' => $approvalRequest->id,
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => 'submitted',
            'comment' => 'Request submitted',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notification ke Manager
        |--------------------------------------------------------------------------
        */

        $managers = User::where('role', 'manager')->get();

        foreach ($managers as $manager) {
            $manager->notify(
                new ApprovalRequestNotification(
                    $approvalRequest,
                    'Pengajuan "' . $approvalRequest->title . '" menunggu persetujuan Anda.'
                )
            );
        }

        return response()->json([
            'message' => 'Request berhasil disubmit.',
            'data' => $approvalRequest
        ]);
    }

    /**
     * Approve Request
     *
     * Submitted -> Approved
     */
    public function approve(
        ApprovalActionRequest $request,
        ApprovalRequest $approvalRequest
    ) {
        /*
        |--------------------------------------------------------------------------
        | Hanya Manager yang boleh approve
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->role !== 'manager') {
            return response()->json([
                'message' => 'Hanya Manager yang dapat melakukan approval.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya Submitted yang boleh di-approve
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->status !== 'submitted') {
            return response()->json([
                'message' => 'Hanya request yang berstatus submitted yang dapat di-approve.'
            ], 400);
        }

        $oldStatus = $approvalRequest->status;

        $approvalRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Approval History
        |--------------------------------------------------------------------------
        */

        ApprovalHistory::create([
            'approval_request_id' => $approvalRequest->id,
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => 'approved',
            'comment' => $request->comment,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notification ke Employee
        |--------------------------------------------------------------------------
        */

        $employee = User::find($approvalRequest->user_id);

        if ($employee) {
            $employee->notify(
                new ApprovalRequestNotification(
                    $approvalRequest,
                    'Pengajuan "' . $approvalRequest->title . '" telah disetujui oleh Manager.'
                )
            );
        }

        return response()->json([
            'message' => 'Request berhasil di-approve.',
            'data' => $approvalRequest,
        ]);
    }

    /**
     * Reject Request
     *
     * Submitted -> Rejected
     */
    public function reject(
        ApprovalActionRequest $request,
        ApprovalRequest $approvalRequest
    ) {
        /*
        |--------------------------------------------------------------------------
        | Hanya Manager yang boleh reject
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->role !== 'manager') {
            return response()->json([
                'message' => 'Hanya Manager yang dapat melakukan approval.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya Submitted yang boleh di-reject
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->status !== 'submitted') {
            return response()->json([
                'message' => 'Hanya request yang berstatus submitted yang dapat di-reject.'
            ], 400);
        }

        $oldStatus = $approvalRequest->status;

        $approvalRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Approval History
        |--------------------------------------------------------------------------
        */

        ApprovalHistory::create([
            'approval_request_id' => $approvalRequest->id,
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => 'rejected',
            'comment' => $request->comment,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notification ke Employee
        |--------------------------------------------------------------------------
        */

        $employee = User::find($approvalRequest->user_id);

        if ($employee) {
            $employee->notify(
                new ApprovalRequestNotification(
                    $approvalRequest,
                    'Pengajuan "' . $approvalRequest->title . '" ditolak oleh Manager. Alasan: ' . $request->comment
                )
            );
        }

        return response()->json([
            'message' => 'Request berhasil di-reject.',
            'data' => $approvalRequest,
        ]);
    }

    /**
     * Approval History
     */
    public function history(ApprovalRequest $approvalRequest)
    {
        return response()->json([
            'success' => true,
            'message' => 'History approval berhasil diambil.',
            'data' => $approvalRequest->histories()
                ->with('user:id,name,email,role')
                ->oldest()
                ->get(),
        ]);
    }

    /**
     * Approval Timeline
     */
    public function timeline(
        Request $request,
        ApprovalRequest $approvalRequest
    ) {
        $query = $approvalRequest->histories()
            ->with('user:id,name,email,role');

        if ($request->query('sort') === 'latest') {
            $query->latest();
        } else {
            $query->oldest();
        }

        return response()->json([
            'success' => true,
            'message' => 'Timeline approval berhasil diambil.',
            'sort' => $request->query('sort', 'oldest'),
            'data' => $query->get(),
        ]);
    }
}