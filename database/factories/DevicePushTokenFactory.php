<?php

namespace Database\Factories;

use App\Models\DevicePushToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DevicePushToken>
 */
class DevicePushTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => fake()->unique()->regexify('[A-Za-z0-9\-\_:]{140}'),
            'user_identifier' => (string) fake()->numberBetween(1, 99999),
            'platform' => fake()->randomElement(['android', 'ios']),
            'is_active' => true,
            'last_seen_at' => now(),
        ];
    }
}
