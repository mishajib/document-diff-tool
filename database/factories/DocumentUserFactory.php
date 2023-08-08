<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentUser>
 */
class DocumentUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_id'         => fake()->randomElement(Document::pluck('id')->toArray()),
            'user_id'             => fake()->randomElement(User::where('role', User::CLIENT)->pluck('id')->toArray()),
            'last_viewed_version' => fake()->numberBetween(1, 100),
        ];
    }
}
