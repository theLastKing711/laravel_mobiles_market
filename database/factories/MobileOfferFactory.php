<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MobileOffer>
 */
class MobileOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_in_english' => fake()->name(),
            'name_in_arabic' => fake()->name(),
            'price_in_usd' => fake()->numberBetween(75, 1500),
            'screen_size' => fake()->numberBetween(5, 8),
            'screen_type' => fake()->randomElement(['ips', 'oled', 'amoled']),
            'ram' => fake()->numberBetween(4, 12),
            'cpu' => fake()->name(),
            'storage' => fake()->randomElement([256, 512, 1024]),
            'battery_size' => fake()->numberBetween(4000, 8000),
            'battery_health' => fake()->numberBetween(80, 100),
            'number_of_sims' => fake()->numberBetween(1, 2),
            'number_of_esims' => fake()->numberBetween(0, 2),
            'color' => fake()->randomElement(['pink', 'blue', 'red', 'yellow']),
        ];
    }
}
