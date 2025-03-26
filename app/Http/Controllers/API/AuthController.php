<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        $user = User::create($fields);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ])->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $fields = $request->validated();

        $user = User::Where('username', $fields['username'])->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ])->setStatusCode(404);
        }

        if (Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'token' => $user->createToken($user->username)->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
            'user' => $user
        ])->setStatusCode(401);

    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
