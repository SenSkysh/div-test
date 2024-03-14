<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class RequestFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(["Active","Resolved"]);
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'status' => $status,
            'message' => fake()->paragraph(),
            'comment' => $status == 'Active' ? null : fake()->paragraph()
        ];
    }
}
