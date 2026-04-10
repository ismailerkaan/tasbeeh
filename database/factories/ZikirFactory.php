<?php

namespace Database\Factories;

use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Zikir>
 */
class ZikirFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'zikir_category_id' => ZikirCategory::factory(),
            'zikir' => fake()->sentence(3),
            'anlami' => fake()->sentence(),
            'fazileti' => fake()->paragraph(),
            'hedef' => fake()->numberBetween(10, 1000),
        ];
    }
}
