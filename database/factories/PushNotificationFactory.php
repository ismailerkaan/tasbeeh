<?php

namespace Database\Factories;

use App\Models\PushNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PushNotification>
 */
class PushNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'body' => fake()->sentence(),
            'target_type' => fake()->randomElement(['all', 'user']),
            'target_user_identifier' => null,
            'data' => null,
            'status' => 'queued',
            'success_count' => 0,
            'failed_count' => 0,
            'error_message' => null,
            'sent_at' => null,
        ];
    }
}
