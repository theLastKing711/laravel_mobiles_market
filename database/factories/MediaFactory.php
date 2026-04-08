<?php

namespace Database\Factories;

use App\Enum\FileUploadDirectory;
use App\Models\MobileOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media::class>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_url' => fake()->url(),
            'file_name' => fake()->word(),
            'file_type' => 'image',
            'size' => fake()->numberBetween(4000, 10000),
            'collection_name' => fake()->randomElement(FileUploadDirectory::cases()),
            'thumbnail_url' => fake()->word().'_thumb.webp',
            'public_id' => fake()->word(),
        ];
    }

    public function withCollectionName(FileUploadDirectory $file_upload_directory): static
    {
        return $this->state(fn (array $attributes) => [
            'collection_name' => $file_upload_directory,
        ]);
    }

    public function withImageUrl(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_url' => 'https://media.wired.com/photos/5b22c5c4b878a15e9ce80d92/master/pass/iphonex-TA.jpg',
            'thumbnail_url' => 'https://media.wired.com/photos/5b22c5c4b878a15e9ce80d92/master/pass/iphonex-TA.jpg',
        ]);
    }

    public function forMobileOfferWithId(int $id): static
    {
        return $this->state(fn (array $attributes) => [
            'medially_type' => MobileOffer::class,
            'medially_id' => $id,
        ]);
    }

    public function forUserWithId(int $id): static
    {
        return $this->state(fn (array $attributes) => [
            'medially_type' => User::class,
            'medially_id' => $id,
        ]);
    }
}
