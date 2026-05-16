<?php

namespace Database\Factories;

use App\Models\Hadis;
use App\Models\HadisCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hadis>
 */
class HadisFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hadis_category_id' => HadisCategory::factory(),
            'source' => fake()->words(2, true),
            'hadis' => fake()->paragraph(),
            'turkce_meali' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
