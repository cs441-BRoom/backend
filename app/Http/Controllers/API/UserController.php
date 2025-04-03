<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\DestroyRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Requests\Users\UploadRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository
    ) { }

    public function upload(UploadRequest $request)
    {
        $request->validated();

        try {
            $file = $request->file('image');
            $file->storeAs('users/profiles/' . auth()->id(), 'profile.' . $file->getClientOriginalExtension());
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->userRepository->update($validated, auth()->id());
            $user = $this->userRepository->getById(auth()->id());

            return response()->json(new UserResource($user), 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request)
    {
        $validated = $request->validated();

        try {
            if (Hash::check($validated['password'], auth()->user()->password)) {
                $this->userRepository->delete(auth()->id());

                return response()->json([
                    'message' => 'User account has been successfully deleted.'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Password is not correct.'
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
