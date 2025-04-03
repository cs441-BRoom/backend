<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;

class WorkspaceMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [];

        $workspaces = Workspace::all();
        $users = User::all();

        foreach ($workspaces as $workspace) {
            // เจ้าของ workspace เป็น admin
            $members[] = [
                'workspace_id' => $workspace->workspace_id,
                'user_id' => $workspace->created_by,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // เพิ่มสมาชิกคนอื่นแบบสุ่ม
            $otherUsers = $users->where('user_id', '!=', $workspace->created_by)->random(rand(2, 4));

            foreach ($otherUsers as $user) {
                $members[] = [
                    'workspace_id' => $workspace->workspace_id,
                    'user_id' => $user->user_id,
                    'role' => 'member',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        WorkspaceMember::insert($members);
    }
}
