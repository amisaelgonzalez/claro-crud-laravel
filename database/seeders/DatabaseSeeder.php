<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WorldSeeder::class);
        $this->call(UserSeeder::class);
        \App\Models\User::factory()->has(\App\Models\Email::factory()->count(25))->count(60)->create();
    }
}
