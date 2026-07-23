<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovalActionRequest;
use App\Models\ApprovalHistory;
use App\Models\ApprovalRequest;

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
     * @OA\Post(
     *     path="/api/approval-requests/{id}/submit",
     *     tags={"Approval Workflow"},
     *     summary="Submit Request",
     *     description="Employee submit draft request menjadi submitted.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Request berhasil disubmit"
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Request sudah diproses"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/approval-requests/{id}/approve",
     *     tags={"Approval Workflow"},
     *     summary="Approve Request",
     *     description="Manager menyetujui request.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"comment"},
     *             @OA\Property(
     *                 property="comment",
     *                 type="string",
     *                 example="Request disetujui."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Request berhasil diapprove"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/approval-requests/{id}/reject",
     *     tags={"Approval Workflow"},
     *     summary="Reject Request",
     *     description="Manager menolak request.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"comment"},
     *             @OA\Property(
     *                 property="comment",
     *                 type="string",
     *                 example="Budget belum tersedia."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Request berhasil direject"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/approval-requests/{id}/history",
     *     tags={"Approval History"},
     *     summary="Approval History",
     *     description="Menampilkan seluruh riwayat approval request.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="History berhasil diambil"
     *     )
     * )
     */
    public function history(ApprovalRequest $approvalRequest)
    {
        $history = $approvalRequest->histories()
            ->with('user:id,name,email,role')
            ->oldest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'History approval berhasil diambil.',
            'data' => $history,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/approval-requests/{id}/timeline",
     *     tags={"Approval History"},
     *     summary="Approval Timeline",
     *     description="Menampilkan timeline perubahan status approval request.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Timeline berhasil diambil"
     *     )
     * )
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