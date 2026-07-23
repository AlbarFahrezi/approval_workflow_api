<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;


/**
 * @OA\Tag(
 *     name="User Management",
 *     description="Admin User Management API"
 * )
 */
class UserController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan semua user
     */

    /**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"User Management"},
 *     summary="Get All Users",
 *     description="Menampilkan seluruh data user. Hanya dapat diakses Admin.",
 *     security={{"sanctum":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mengambil data user"
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     )
 * )
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
     * Membuat user baru (Admin)
     */

    /**
 * @OA\Post(
 *     path="/api/users",
 *     tags={"User Management"},
 *     summary="Create User",
 *     description="Membuat user baru. Hanya Admin.",
 *     security={{"sanctum":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","role"},
 *
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="password"),
 *             @OA\Property(property="role", type="string", example="employee")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="User berhasil dibuat"
 *     )
 * )
 */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return $this->success(
            'User berhasil dibuat.',
            $user,
            201
        );
    }

    /**
     * Detail user
     */

    /**
 * @OA\Get(
 *     path="/api/users/{id}",
 *     tags={"User Management"},
 *     summary="Detail User",
 *     description="Melihat detail user berdasarkan ID.",
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
 *         description="Detail user berhasil diambil"
 *     )
 * )
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

    /**
 * @OA\Put(
 *     path="/api/users/{id}",
 *     tags={"User Management"},
 *     summary="Update User",
 *     description="Mengubah data user.",
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
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="role", type="string")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="User berhasil diperbarui"
 *     )
 * )
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

    /**
 * @OA\Delete(
 *     path="/api/users/{id}",
 *     tags={"User Management"},
 *     summary="Delete User",
 *     description="Menghapus user berdasarkan ID.",
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
 *         description="User berhasil dihapus"
 *     )
 * )
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