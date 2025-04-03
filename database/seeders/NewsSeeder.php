<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $newsData = [];

        $workspaceMembers = WorkspaceMember::all();

        // สร้าง 5 ข่าวในแต่ละ workspace
        foreach ($workspaceMembers as $member) {
            for ($i = 1; $i <= 5; $i++) {
                $newsData[] = [
                    'workspace_id' => $member->workspace_id,
                    'title' => 'News ' . $i . ' from workspace ' . $member->workspace_id,
                    'content' => 'This is content of news ' . $i . ' from workspace ' . $member->workspace_id,
                    'created_by' => $member->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        News::insert($newsData);
    }
}
