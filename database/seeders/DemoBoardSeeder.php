<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class DemoBoardSeeder extends Seeder
{
    public function run()
    {
        // Example users (adjust IDs/emails as per your setup)
        $owner = User::firstOrCreate([
            'email' => 'maryam786sohail@gmail.com'
        ], [
            'name' => 'Maryam Sohail Ahmed',
            'password' => bcrypt('password'),
        ]);

        $pending = User::firstOrCreate([
            'email' => 'bpk14483@gmail.com'
        ], [
            'name' => 'Pending Invite',
            'password' => bcrypt('password'),
        ]);

        // Demo tasks
        $tasks = [
            [
                'title' => 'Design landing page mockup',
                'description' => 'UI/UX draft for homepage',
                'status' => 'todo',
                'priority' => 'high',
                'assignee_id' => $owner->id,
                'due_date' => '2026-06-02',
                'creator_id' => $owner->id,
                'created_by' => $owner->id,   // ✅ Added
                'team_id' => 1,
                'workspace_id' => 1,
            ],
            [
                'title' => 'Write project requirements doc',
                'description' => 'Functional + non-functional requirements',
                'status' => 'todo',
                'priority' => 'medium',
                'assignee_id' => $pending->id,
                'due_date' => '2026-06-05',
                'creator_id' => $owner->id,
                'created_by' => $owner->id,   // ✅ Added
                'team_id' => 1,
                'workspace_id' => 1,
            ],
            [
                'title' => 'Set up CI/CD pipeline',
                'description' => 'GitHub Actions + deployment',
                'status' => 'in_progress',
                'priority' => 'high',
                'assignee_id' => $pending->id,
                'due_date' => '2026-05-30',
                'creator_id' => $owner->id,
                'created_by' => $owner->id,   // ✅ Added
                'team_id' => 1,
                'workspace_id' => 1,
            ],
            [
                'title' => 'API integration testing',
                'description' => 'Test endpoints with Postman',
                'status' => 'in_progress',
                'priority' => 'medium',
                'assignee_id' => $owner->id,
                'due_date' => '2026-05-28',
                'creator_id' => $owner->id,
                'created_by' => $owner->id,   // ✅ Added
                'team_id' => 1,
                'workspace_id' => 1,
            ],
            [
                'title' => 'Initial project setup & repo',
                'description' => 'Laravel + MySQL base project',
                'status' => 'done',
                'priority' => 'low',
                'assignee_id' => $owner->id,
                'due_date' => '2026-05-20',
                'creator_id' => $owner->id,
                'created_by' => $owner->id,   // ✅ Added
                'team_id' => 1,
                'workspace_id' => 1,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
