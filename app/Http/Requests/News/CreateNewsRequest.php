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
        return true;
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
            'files' => 'list',
            'files.*' => 'mimes:jpg,png|max:2048'
        ];
    }
}
