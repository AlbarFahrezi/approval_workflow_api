<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApprovalRequestRequest;
use App\Http\Requests\UpdateApprovalRequestRequest;
use App\Models\ApprovalRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Approval Requests",
 *     description="Approval Request Management"
 * )
 */
class ApprovalRequestController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar request berdasarkan role
     */

    /**
     * @OA\Get(
     *     path="/api/approval-requests",
     *     tags={"Approval Requests"},
     *     summary="Get Approval Requests",
     *     description="Menampilkan daftar approval request.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter status",
     *         @OA\Schema(type="string", example="submitted")
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Cari berdasarkan title",
     *         @OA\Schema(type="string", example="Laptop")
     *     ),
     *
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sorting data",
     *         @OA\Schema(type="string", example="latest")
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah data per halaman",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = ApprovalRequest::with('user');

        /*
        |--------------------------------------------------------------------------
        | Role Access
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'employee') {

            /*
            | Employee hanya melihat request miliknya sendiri.
            */
            $query->where('user_id', $user->id);

        } elseif ($user->role === 'manager') {

            /*
            | Manager melihat request yang sudah submitted
            | untuk diproses melalui approval.
            |
            | Draft milik Employee maupun draft milik Manager
            | tidak ditampilkan pada daftar approval Manager.
            */
            $query->where('status', 'submitted');

        } elseif ($user->role === 'admin') {

            /*
            | Admin dapat melihat semua request.
            */
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'title',
                    'like',
                    '%' . $request->search . '%'
                )->orWhere(
                    'description',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter User - Admin Only
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'admin' &&
            $request->filled('user_id')
        ) {
            $query->where('user_id', $request->user_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($request->get('sort') === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $approvalRequests = $query->paginate(
            $request->get('per_page', 10)
        );

        return $this->success(
            'Data request berhasil diambil.',
            $approvalRequests
        );
    }

    /**
     * Membuat request baru
     */

    /**
     * @OA\Post(
     *     path="/api/approval-requests",
     *     tags={"Approval Requests"},
     *     summary="Create Request",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description"},
     *
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Pembelian Laptop"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Laptop untuk divisi IT"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Request berhasil dibuat"
     *     )
     * )
     */
    public function store(StoreApprovalRequestRequest $request)
    {
        $approvalRequest = ApprovalRequest::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'draft',
        ]);

        return $this->success(
            'Request berhasil dibuat.',
            $approvalRequest,
            201
        );
    }

    /**
     * Detail request
     */

    /**
     * @OA\Get(
     *     path="/api/approval-requests/{id}",
     *     tags={"Approval Requests"},
     *     summary="Detail Request",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function show(
        Request $request,
        ApprovalRequest $approvalRequest
    ) {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Employee Access
        |--------------------------------------------------------------------------
        |
        | Employee hanya boleh melihat request miliknya sendiri.
        |
        */

        if (
            $user->role === 'employee' &&
            $approvalRequest->user_id !== $user->id
        ) {
            return $this->error(
                'Anda tidak memiliki akses ke request ini.',
                null,
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Manager Access
        |--------------------------------------------------------------------------
        |
        | Manager dapat melihat:
        |
        | 1. Draft miliknya sendiri
        | 2. Request milik user lain yang sudah masuk proses approval
        |    yaitu submitted, approved, atau rejected.
        |
        | Manager tidak dapat melihat draft milik user lain.
        |
        */

        if (
            $user->role === 'manager' &&
            $approvalRequest->status === 'draft' &&
            $approvalRequest->user_id !== $user->id
        ) {
            return $this->error(
                'Manager tidak dapat melihat draft milik user lain.',
                null,
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        |
        | Admin dapat melihat semua request.
        |
        */

        return $this->success(
            'Detail request berhasil diambil.',
            $approvalRequest->load('user')
        );
    }

    /**
     * Update request
     */

    /**
     * @OA\Put(
     *     path="/api/approval-requests/{id}",
     *     tags={"Approval Requests"},
     *     summary="Update Request",
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
     *             @OA\Property(
     *                 property="title",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Updated"
     *     )
     * )
     */
    public function update(
        UpdateApprovalRequestRequest $request,
        ApprovalRequest $approvalRequest
    ) {
        /*
        |--------------------------------------------------------------------------
        | Hanya Draft yang boleh diedit
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->status !== 'draft') {
            return $this->error(
                'Request yang sudah disubmit tidak dapat diedit.',
                null,
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya Pemilik Request yang Boleh Edit
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->user_id !== $request->user()->id) {
            return $this->error(
                'Anda tidak memiliki akses ke request ini.',
                null,
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Data
        |--------------------------------------------------------------------------
        */

        $approvalRequest->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return $this->success(
            'Request berhasil diperbarui.',
            $approvalRequest
        );
    }

    /**
     * Hapus request
     */

    /**
     * @OA\Delete(
     *     path="/api/approval-requests/{id}",
     *     tags={"Approval Requests"},
     *     summary="Delete Request",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Deleted"
     *     )
     * )
     */
    public function destroy(
        Request $request,
        ApprovalRequest $approvalRequest
    ) {
        /*
        |--------------------------------------------------------------------------
        | Hanya Draft yang boleh dihapus
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->status !== 'draft') {
            return $this->error(
                'Request yang sudah disubmit tidak dapat dihapus.',
                null,
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya Pemilik Request yang Boleh Menghapus
        |--------------------------------------------------------------------------
        */

        if ($approvalRequest->user_id !== $request->user()->id) {
            return $this->error(
                'Anda tidak memiliki akses ke request ini.',
                null,
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Data
        |--------------------------------------------------------------------------
        */

        $approvalRequest->delete();

        return $this->success(
            'Request berhasil dihapus.'
        );
    }
}