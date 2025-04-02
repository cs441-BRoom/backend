<?php

namespace App\Http\Requests\Assignments;

use App\Models\Workspace;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // check whether this guy is workspace owner??
        $user = auth()->user();
        $workspaceId = $this->input('workspace_id');

        $isOwner = Workspace::where('workspace_id', $workspaceId)
            ->where('created_by', $user->user_id)
            ->exists();

        return $isOwner;
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
            'description' => 'string|max:255',
            'due_date' => 'required|date|after:today',
            'files' => 'list',
            'files.*' => 'mimes:jpg,png,pdf|max:2048'
        ];
    }
}
