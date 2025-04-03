<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\News;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [];

        $newsList = News::all();

        foreach ($newsList as $news) {
            // ดึงสมาชิก workspace ที่สามารถคอมเมนต์ข่าวนี้ได้
            $workspaceMembers = WorkspaceMember::where('workspace_id', $news->workspace_id)->pluck('user_id');

            if ($workspaceMembers->isEmpty()) {
                continue;
            }

            // สุ่มสมาชิก workspace มาคอมเมนต์
            $commenters = $workspaceMembers->random(min(rand(2, 4), $workspaceMembers->count()));

            foreach ($commenters as $userId) {
                $comments[] = [
                    'news_id' => $news->news_id,
                    'created_by' => $userId,
                    'content' => 'This is a comment from user ' . $userId . ' on news ' . $news->news_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Comment::insert($comments);
    }
}
