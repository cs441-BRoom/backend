<?php

namespace App\Http\Requests\News;

use App\Models\News;
use App\Models\Workspace;
use Illuminate\Foundation\Http\FormRequest;

class ShowNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $workspaceId = $this->route('workspaceId');
        $newsId = $this->route('newsId');

        // ตรวจสอบว่า workspace มีอยู่จริง
        $workspace = Workspace::where('workspace_id', $workspaceId)->first();
        if (!$workspace) {
            return false;
        }

        // ตรวจสอบว่า user เป็นเจ้าของ workspace หรือเป็นสมาชิก
        $isAuthorized = $workspace->created_by == $user->user_id ||
            $workspace->users()->where('workspace_members.user_id', $user->user_id)->exists();

        // ตรวจสอบว่า news นี้อยู่ใน workspace ที่ระบุ
        $newsExists = News::where('news_id', $newsId)->where('workspace_id', $workspaceId)->exists();

        return $isAuthorized && $newsExists;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [

        ];
    }
}
