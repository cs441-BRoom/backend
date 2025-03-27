<?php

namespace App\Http\Requests\Like;

use App\Models\News;
use App\Models\WorkspaceMember;
use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = auth()->id();
        $newsId = $this->input('news_id');
        $news = News::findOrFail($newsId);

        $isMember = WorkspaceMember::where('user_id', $userId)
            ->where('workspace_id', $news->workspace_id)
            ->exists();

        $isOwner = $news->workspace->created_by == $userId;

        return $isMember || $isOwner;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'news_id' => 'required|exists:news,news_id',
        ];
    }
}
