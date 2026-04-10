<?php

namespace Database\Factories;

use App\Models\DuaCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DuaCategory>
 */
class DuaCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'is_active' => fake()->boolean(80),
        ];
    }
}
