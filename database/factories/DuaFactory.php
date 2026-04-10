<?php

namespace Database\Factories;

use App\Models\Dua;
use App\Models\DuaCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dua>
 */
class DuaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dua_category_id' => DuaCategory::factory(),
            'source' => fake()->randomElement(['Kur-an', 'Hadis', 'Klasik Kaynak']),
            'dua' => fake()->paragraph(),
            'turkce_meali' => fake()->paragraph(),
            'is_active' => fake()->boolean(85),
        ];
    }
}
