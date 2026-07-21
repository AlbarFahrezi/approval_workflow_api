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

        // Admin melihat semua request
        if ($user->role === 'admin') {

            $approvalRequests = ApprovalRequest::with('user')
                ->latest()
                ->get();

        }
        // Manager hanya melihat request yang menunggu approval
        elseif ($user->role === 'manager') {

            $approvalRequests = ApprovalRequest::with('user')
                ->where('status', 'submitted')
                ->latest()
                ->get();

        }
        // Employee hanya melihat request miliknya
        else {

            $approvalRequests = ApprovalRequest::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

        }

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
    public function show(ApprovalRequest $approvalRequest)
    {
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
    public function destroy(ApprovalRequest $approvalRequest)
    {
        if ($approvalRequest->status === 'approved') {
            return $this->error(
                'Request yang sudah disetujui tidak dapat dihapus.',
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