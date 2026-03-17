<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Foundation>
 */
class FoundationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'subdomain' => $this->faker->unique()->word() . '.localhost',
            'status' => 'pending',
            'plan_id' => null,
            'trial_ends_at' => null,
            'subscription_ends_at' => null,
            'tenant_id' => null,
        ];
    }
}
