<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create fixed demo users for easy testing
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@teamtasks.test',
            'password' => Hash::make('password'),
        ]);

        $john = User::create([
            'name'     => 'John Doe',
            'email'    => 'john@teamtasks.test',
            'password' => Hash::make('password'),
        ]);

        $sara = User::create([
            'name'     => 'Sara Khan',
            'email'    => 'sara@teamtasks.test',
            'password' => Hash::make('password'),
        ]);

        // Create workspace owned by admin
        $workspace = Workspace::create([
            'name'        => 'Acme Corp',
            'description' => 'Main product development workspace.',
            'owner_id'    => $admin->id,
        ]);

        // Add all users as members
        $workspace->members()->attach($admin->id, ['role' => 'owner']);
        $workspace->members()->attach($john->id, ['role' => 'member']);
        $workspace->members()->attach($sara->id, ['role' => 'member']);

        // Seed tasks
        $tasks = [
            [
                'title'       => 'Set up CI/CD pipeline',
                'description' => 'Configure GitHub Actions for automated tests and deployments.',
                'status'      => Task::STATUS_COMPLETED,
                'priority'    => Task::PRIORITY_HIGH,
                'assigned_to' => $john->id,
                'due_date'    => now()->subDays(5),
            ],
            [
                'title'       => 'Design database schema',
                'description' => 'Create the ERD for the new reporting module.',
                'status'      => Task::STATUS_IN_PROGRESS,
                'priority'    => Task::PRIORITY_HIGH,
                'assigned_to' => $sara->id,
                'due_date'    => now()->addDays(2),
            ],
            [
                'title'       => 'Write API documentation',
                'description' => 'Document all endpoints using OpenAPI/Swagger.',
                'status'      => Task::STATUS_PENDING,
                'priority'    => Task::PRIORITY_MEDIUM,
                'assigned_to' => $john->id,
                'due_date'    => now()->addDays(7),
            ],
            [
                'title'       => 'Fix authentication bug',
                'description' => 'Token refresh fails when session expires on mobile.',
                'status'      => Task::STATUS_PENDING,
                'priority'    => Task::PRIORITY_HIGH,
                'assigned_to' => null,
                'due_date'    => now()->addDay(),
            ],
            [
                'title'       => 'Add unit tests for TaskService',
                'description' => null,
                'status'      => Task::STATUS_PENDING,
                'priority'    => Task::PRIORITY_LOW,
                'assigned_to' => $sara->id,
                'due_date'    => now()->addDays(10),
            ],
        ];

        foreach ($tasks as $taskData) {
            $workspace->tasks()->create(array_merge($taskData, ['created_by' => $admin->id]));
        }

        // Add a second workspace owned by John
        $workspace2 = Workspace::create([
            'name'        => 'Side Project',
            'description' => "John's personal project workspace.",
            'owner_id'    => $john->id,
        ]);

        $workspace2->members()->attach($john->id, ['role' => 'owner']);
        $workspace2->members()->attach($sara->id, ['role' => 'member']);

        $workspace2->tasks()->create([
            'title'       => 'Build landing page',
            'status'      => Task::STATUS_PENDING,
            'priority'    => Task::PRIORITY_MEDIUM,
            'assigned_to' => $sara->id,
            'created_by'  => $john->id,
            'due_date'    => now()->addDays(14),
        ]);
    }
}
