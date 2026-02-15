<?php

namespace Database\Factories;


use App\Models\PhoneBrand;
use Illuminate\Database\Eloquent\Factories\Factory;



class PhoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => fake()->phoneNumber(),
            'phone_brand_id' => PhoneBrand::query()->get()->random()->id,
        ];
    }
}
