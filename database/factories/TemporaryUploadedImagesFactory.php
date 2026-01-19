<?php

namespace Database\Factories;

use App\Enum\FileUploadDirectory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemporaryUploadedImages>
 */
class TemporaryUploadedImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uploadable_type' => User::class,
            'uploadable_id' => 1,
            'public_id' => fake()->sha1(),
            'file_name' => fake()->word(),
            'file_url' => fake()->url(),
            'thumbnail_url' => fake()->word().'_thumb.webp',
            'size' => fake()->numberBetween(4000, 10000),
            'file_type' => 'image',
            'collection_name' => fake()->randomElement(FileUploadDirectory::cases()),
        ];
    }
}
