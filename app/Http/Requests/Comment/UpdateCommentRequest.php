<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $commentId = $this->route('commentId');

        $comment = Comment::find($commentId);

        if (!$comment) {
            throw new AuthorizationException('Comment not found.');
        }

        if ($comment->created_by !== $user->user_id) {
            throw new AuthorizationException('You are not authorized to update this comment.');
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
            'content' => 'sometimes|string|max:1000',
        ];
    }
}
