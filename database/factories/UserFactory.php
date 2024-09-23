<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'fonction' => $this->faker->word,
            'email' => $this->faker->unique()->safeEmail,
            'photo' => $this->faker->imageUrl(),
            'statut' => $this->faker->randomElement(['Bloqué', 'Actif']),
            'role_id' => 1,
            // 'password' => bcrypt('Passer@123'), // Mot de passe par défaut
            'password'=> Hash::make('Passer@123'),
        ];
    }


    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
