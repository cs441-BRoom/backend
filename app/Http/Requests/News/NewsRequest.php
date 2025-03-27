<?php

namespace App\Http\Requests\News;

use App\Models\Workspace;
use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $workspaceId = $this->route('workspaceId');

        $isOwner = Workspace::where('workspace_id', $workspaceId)
            ->where('created_by', $user->user_id)
            ->exists();

        if ($isOwner) {
            return true;
        }

        $isMember = Workspace::where('workspace_id', $workspaceId)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('workspace_members.user_id', $user->user_id);
            })
            ->exists();

        if ($isMember) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
}
