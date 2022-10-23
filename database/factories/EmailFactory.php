<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject'   => $this->faker->word(),
            'to'        => $this->faker->safeEmail(),
            'message'   => $this->faker->text(200),
            'status'    => $this->faker->randomElement(['PENDING', 'SENT', 'UNSUCCESSFUL']),
            'user_id'   => null,
        ];
    }
}
