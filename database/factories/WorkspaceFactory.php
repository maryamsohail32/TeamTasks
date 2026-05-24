<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->company(),
            'description' => fake()->sentence(),
            'owner_id'    => User::factory(),
        ];
    }
}
