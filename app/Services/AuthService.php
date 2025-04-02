<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    /**
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Invalid credentials.']
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken($user->username)->plainTextToken
        ];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
    }



}
