<?php

namespace App\Http\Requests\Like;

use App\Models\Like;
use Illuminate\Foundation\Http\FormRequest;

class DislikeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = auth()->id();
        $newsId = $this->route('newsId');
        $like = Like::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();

        return $like !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
