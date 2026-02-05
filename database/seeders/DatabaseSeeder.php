<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
       User::factory()
       ->has(Phone::factory()->count(5), 'phones')
       ->count(100)
       ->create();
    }
}
