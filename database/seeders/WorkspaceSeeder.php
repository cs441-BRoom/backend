<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $workspaces = [];

        $users = User::all();

        // เพิ่ม 5 workspace สำหรับแต่ละ user
        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                $workspaces[] = [
                    'name' => 'Workspace ' . $i . ' of ' . $user->username,
                    'description' => 'This is the ' . $i . 'th workspace for ' . $user->username,
                    'join_code' => Str::random(8),
                    'created_by' => $user->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Workspace::insert($workspaces);
    }
}
