<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class PhoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => fake()->phoneNumber(),
        ];
    }
}
