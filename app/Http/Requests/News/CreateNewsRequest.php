<?php

namespace App\Http\Requests\News;

use App\Models\Workspace;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $workspaceId = $this->input('workspace_id');

        $isAuthorized = Workspace::where('workspace_id', $workspaceId)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('workspace_members.user_id', $user->user_id);
            })
            ->orWhere('created_by', $user->user_id)
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
            'workspace_id' => 'required|exists:workspaces,workspace_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }
}
