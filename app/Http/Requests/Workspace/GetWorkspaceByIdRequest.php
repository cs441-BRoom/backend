<?php

namespace App\Http\Requests\Workspace;

use App\Models\Workspace;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class GetWorkspaceByIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
            $workspaceId = (int) $this->route('workspaceId'); // แปลงให้เป็น int

        $isAuthorized = Workspace::where('workspace_id', $workspaceId)
            ->where(function ($query) use ($user) {
                $query->whereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('workspace_members.user_id', $user->user_id);
                })
                    ->orWhere('created_by', $user->user_id);
            })
            ->exists();

        if (!$isAuthorized) {
            throw new AuthorizationException('You are not authorized to access this workspace.');
        }

        return $isAuthorized;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        ];
    }
}
