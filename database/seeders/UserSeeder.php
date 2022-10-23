<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrator
        $user = User::create([
            'name'              => 'admin',
            'email'             => 'admin@admin.com',
            'password'          => Hash::make('password'),
            'phone'             => 9912345678,
            'identification'    => '123456789-1',
            'birthday'          => '1995-01-23',
            'role'              => UserRoleEnum::ADMIN,
            'city_id'           => 31501
        ]);

        $user->markEmailAsVerified();
    }
}
