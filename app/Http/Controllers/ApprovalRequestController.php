<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApprovalRequestRequest;
use App\Http\Requests\UpdateApprovalRequestRequest;
use App\Models\ApprovalRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ApprovalRequestController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar request berdasarkan role
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

            // Employee hanya melihat request miliknya
            $query->where('user_id', $user->id);

        } elseif ($user->role === 'manager') {

            // Manager hanya melihat request yang menunggu approval
            $query->where('status', 'submitted');

        }
        // Admin melihat semua request

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Search Title
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Filter User (Admin Only)
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
    public function show(Request $request, ApprovalRequest $approvalRequest)
    {
        $user = $request->user();

        // Employee hanya boleh melihat request miliknya
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

        return $this->success(
            'Detail request berhasil diambil.',
            $approvalRequest->load('user')
        );
    }

    /**
     * Update request
     */
    public function update(UpdateApprovalRequestRequest $request, ApprovalRequest $approvalRequest)
    {
        if ($approvalRequest->status === 'approved') {
            return $this->error(
                'Request yang sudah disetujui tidak dapat diedit.',
                null,
                403
            );
        }

        // Employee hanya boleh mengedit request miliknya
        if (
            auth()->user()->role === 'employee' &&
            $approvalRequest->user_id !== auth()->id()
        ) {
            return $this->error(
                'Anda tidak memiliki akses.',
                null,
                403
            );
        }

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
    public function destroy(Request $request, ApprovalRequest $approvalRequest)
    {
        if ($approvalRequest->status === 'approved') {
            return $this->error(
                'Request yang sudah disetujui tidak dapat dihapus.',
                null,
                403
            );
        }

        // Employee hanya boleh menghapus request miliknya
        if (
            $request->user()->role === 'employee' &&
            $approvalRequest->user_id !== $request->user()->id
        ) {
            return $this->error(
                'Anda tidak memiliki akses.',
                null,
                403
            );
        }

        $approvalRequest->delete();

        return $this->success(
            'Request berhasil dihapus.'
        );
    }
}