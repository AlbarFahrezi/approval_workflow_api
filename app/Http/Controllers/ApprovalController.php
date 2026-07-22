<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovalActionRequest;
use App\Models\ApprovalHistory;
use App\Models\ApprovalRequest;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Employee submit request
     */
    public function submit(ApprovalRequest $approvalRequest)
    {
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

        ApprovalHistory::create([
            'approval_request_id' => $approvalRequest->id,
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => 'submitted',
            'comment' => 'Request submitted',
        ]);

        return response()->json([
            'message' => 'Request berhasil disubmit.',
            'data' => $approvalRequest
        ]);
    }

    /**
     * Manager approve request
     */
    public function approve(
        ApprovalActionRequest $request,
        ApprovalRequest $approvalRequest
    ) {

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

        ApprovalHistory::create([
            'approval_request_id' => $approvalRequest->id,
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => 'approved',
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Request berhasil di-approve.',
            'data' => $approvalRequest,
        ]);
    }

    /**
     * Manager reject request
     */
    public function reject(
        ApprovalActionRequest $request,
        ApprovalRequest $approvalRequest
    ) {

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

        ApprovalHistory::create([
            'approval_request_id' => $approvalRequest->id,
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => 'rejected',
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Request berhasil di-reject.',
            'data' => $approvalRequest,
        ]);
    }

    /**
     * Menampilkan timeline approval
     */
    public function timeline(ApprovalRequest $approvalRequest)
    {
        $timeline = $approvalRequest->histories()
            ->with('user:id,name,email,role')
            ->oldest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Timeline approval berhasil diambil.',
            'data' => $timeline,
        ]);
    }
}