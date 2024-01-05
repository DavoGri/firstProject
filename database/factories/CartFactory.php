<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId=DB::table('users')->pluck('id')->toArray();
        return [
            'user_id'=>$this->faker->randomElement($userId),
            'total_items' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
