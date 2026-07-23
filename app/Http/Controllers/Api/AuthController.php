<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication API"
 * )
 */
class AuthController extends Controller
{
    use ApiResponse;

    /**
 * Register User
 *
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Authentication"},
 *     summary="Register User",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","password_confirmation"},
 *             @OA\Property(property="name", type="string", example="Albar"),
 *             @OA\Property(property="email", type="string", example="albar@gmail.com"),
 *             @OA\Property(property="password", type="string", example="password"),
 *             @OA\Property(property="password_confirmation", type="string", example="password")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Register berhasil"
 *     )
 * )
 */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee', // Selalu employee saat register
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            'Register berhasil.',
            [
                'user' => $user,
                'token' => $token,
            ],
            201
        );
    }

     /**
 * Login User
 *
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Authentication"},
 *     summary="Login",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", example="employee@example.com"),
 *             @OA\Property(property="password", type="string", example="password")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Login berhasil"
 *     )
 * )
 */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error(
                'Email atau password salah.',
                null,
                401
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            'Login berhasil.',
            [
                'user' => $user,
                'token' => $token,
            ]
        );
    }

    /**
 * Logout User
 *
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Authentication"},
 *     summary="Logout",
 *     security={{"sanctum":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Logout berhasil"
 *     )
 * )
 */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logout berhasil.');
    }

   /**
 * Profile User
 *
 * @OA\Get(
 *     path="/api/profile",
 *     tags={"Authentication"},
 *     summary="Profile",
 *     security={{"sanctum":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profile berhasil"
 *     )
 * )
 */
    public function profile(Request $request)
    {
        return $this->success(
            'Data profile berhasil diambil.',
            $request->user()
        );
    }

    /**
 * Update Profile User
 *
 * @OA\Put(
 *     path="/api/profile",
 *     tags={"Authentication"},
 *     summary="Update Profile",
 *     security={{"sanctum":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profile berhasil diperbarui"
 *     )
 * )
 */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return $this->success(
            'Profile berhasil diperbarui.',
            $user
        );
    }

    /**
 * Change Password User
 *
 * @OA\Put(
 *     path="/api/change-password",
 *     tags={"Authentication"},
 *     summary="Change Password",
 *     security={{"sanctum":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="current_password", type="string"),
 *             @OA\Property(property="password", type="string"),
 *             @OA\Property(property="password_confirmation", type="string")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Password berhasil diperbarui"
 *     )
 * )
 */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error(
                'Password lama tidak sesuai.',
                null,
                400
            );
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->success(
            'Password berhasil diperbarui.'
        );
    }
}