<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request){
        try {
            $data = $this->authService->login($request->validated());
            
            $files = Storage::files('users/profiles/'. $data['user']->user_id);
            $file_arr = [];
            foreach ($files as $file) {
                $content = Storage::get($file);
                $base64File = base64_encode($content);
                $mime = Storage::mimeType($file);

                $file_arr[] = [
                    'name' => $file,
                    'base64' => $base64File,
                    'mime_type' => $mime
                ];
            }

            return response()->json([
                'message' => 'Login successfully',
                'user' => new UserResource($data['user']),
                'token' => $data['token'],
                'files' => $file_arr
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
