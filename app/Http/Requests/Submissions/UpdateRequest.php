<?php

namespace App\Http\Requests\Submissions;

use App\Models\Assignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'submission_id' => 'required',
            'assignment_id' => 'required',
            'score' => 'numeric',
            'files' => 'list',
            'files.*' => 'mimes:jpg,png,pdf|max:2048'
        ];
    }
}
