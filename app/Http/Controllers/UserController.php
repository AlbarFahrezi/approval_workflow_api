<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan semua user
     */
    public function index()
    {
        $users = User::latest()->get();

        return $this->success(
            'Data user berhasil diambil.',
            $users
        );
    }

    /**
     * Detail user
     */
    public function show(User $user)
    {
        return $this->success(
            'Detail user berhasil diambil.',
            $user
        );
    }

    /**
     * Update user
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return $this->success(
            'User berhasil diperbarui.',
            $user
        );
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return $this->error(
                'Admin tidak dapat menghapus akunnya sendiri.',
                null,
                403
            );
        }

        $user->delete();

        return $this->success(
            'User berhasil dihapus.'
        );
    }
}