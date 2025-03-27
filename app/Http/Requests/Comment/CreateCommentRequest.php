<?php

namespace App\Http\Requests\Comment;

use App\Models\News;
use App\Models\Workspace;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $newsId = $this->input('news_id');

        // ค้นหา news พร้อม workspace_id
        $news = News::find($newsId);

        if (!$news) {
            throw new AuthorizationException('News not found.');
        }

        $workspaceId = $news->workspace_id;

        // ตรวจสอบสิทธิ์: เป็นเจ้าของ workspace หรือเป็นสมาชิก
        $isAuthorized = Workspace::where('workspace_id', $workspaceId)
            ->where(function ($query) use ($user) {
                $query->where('created_by', $user->user_id)
                    ->orWhereHas('users', function ($subQuery) use ($user) {
                        $subQuery->where('workspace_members.user_id', $user->user_id);
                    });
            })->exists();

        if (!$isAuthorized) {
            throw new AuthorizationException('You are not authorized to comment on this news.');
        }

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
            'news_id' => 'required|exists:news,news_id',
            'content' => 'required|string|max:1000',
        ];
    }
}
