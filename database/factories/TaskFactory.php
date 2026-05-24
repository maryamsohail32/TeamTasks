<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'title'        => fake()->sentence(4),
            'description'  => fake()->optional()->paragraph(),
            'status'       => fake()->randomElement([Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED]),
            'priority'     => fake()->randomElement([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH]),
            'due_date'     => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'assigned_to'  => null,
            'created_by'   => User::factory(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => ['status' => Task::STATUS_PENDING]);
    }

    public function highPriority(): static
    {
        return $this->state(fn() => ['priority' => Task::PRIORITY_HIGH]);
    }

    public function overdue(): static
    {
        return $this->state(fn() => [
            'due_date' => now()->subDays(fake()->numberBetween(1, 10)),
            'status'   => Task::STATUS_PENDING,
        ]);
    }
}
