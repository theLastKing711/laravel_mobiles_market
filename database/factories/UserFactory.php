<?php

namespace Database\Factories;

use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function staticAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::ADMIN);
        });
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::ADMIN);
        });
    }

    public function user(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::USER);
        });
    }

    public function staticStore(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'store',
            'email' => 'store@admin.com',
            'phone_number' => '0968259851',
            'password' => Hash::make('2280'),
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::STORE);
        });
    }

    public function staticUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user',
            'email' => 'user@user.com',
            'phone_number' => '0968259852',
            'password' => Hash::make('2280'),
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::USER);
        });
    }

    public function withPhoneAndPasswordAuth(string $phone_number, string $password): static
    {
        return $this->state(fn (array $attributes) => [
            'phone_number' => $phone_number,
            'password' => Hash::make($password),
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::USER);
        });
    }
}
