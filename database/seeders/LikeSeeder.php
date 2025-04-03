<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\News;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    public function run(): void
    {
        $likes = [];

        $newsList = News::all();

        foreach ($newsList as $news) {
            // ดึงสมาชิกของ workspace ที่ข่าวนี้อยู่
            $workspaceMembers = WorkspaceMember::where('workspace_id', $news->workspace_id)->pluck('user_id');

            if ($workspaceMembers->isEmpty()) {
                continue;
            }

            // สุ่มสมาชิก workspace มา 3-5 คนที่กดไลก์ข่าวนี้
            $likedUsers = $workspaceMembers->random(min(rand(3, 5), $workspaceMembers->count()));

            foreach ($likedUsers as $userId) {
                $likes[] = [
                    'news_id' => $news->news_id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Like::insert($likes);
    }
}
