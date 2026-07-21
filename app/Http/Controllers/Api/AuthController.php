<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register User
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'employee',
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
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logout berhasil.');
    }

    /**
     * Profile User
     */
    public function profile(Request $request)
    {
        return $this->success(
            'Data profile berhasil diambil.',
            $request->user()
        );
    }
}