<?php

namespace Database\Factories;

use App\Models\ZikirCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZikirCategory>
 */
class ZikirCategoryFactory extends Factory
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
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(80),
        ];
    }
}
