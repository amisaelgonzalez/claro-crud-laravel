<?php

namespace Tests;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;
    protected $headers;

    /**
     * Authenticate user.
     *
     * @return \App\Models\User
     */
    protected function getUser()
    {
        $this->user = User::firstOrCreate(
            ['email' =>  'user@phpunit.test'],
            [
                'name'              => 'Jhon Doe',
                'password'          => Hash::make('password'),
                'phone'             => 9912345678,
                'identification'    => '123456789-0',
                'birthday'          => '1995-12-30',
                'role'              => UserRoleEnum::USER,
                'city_id'           => 31501,
                'terms_conditions'  => true,
                'privacy_policies'  => true,
            ]
        );

        return $this->user;
    }

    /**
     * Http basic headers.
     *
     * @return Array
     */
    protected function getHeaders()
    {
        $user = $this->getUser();
        $user->tokenDelete();
        $token = $user->createToken('claroinsurance_app')->accessToken;

        $this->headers = ['Authorization' => 'Bearer '. $token];

        return $this->headers;
    }

    /**
     * User reset password.
     *
     * @return Array
     */
    protected function resetPassword()
    {
        $this->user->password = Hash::make('password');
        $this->user->update();
    }

    /**
     * Save json from in doc storage.
     *
     * @return void
     */
    protected function jsonDocStorage($response, $path)
    {
        if ($response->assertSuccessful()) {
            Storage::disk('doc')->put($path, $response->getContent());
        }
    }

    /**
     * Get pagination json structure.
     *
     * @return Array
     */
    protected function getPaginationJsonStructure($modelJsonStructure)
    {
        return [
            'current_page',
            'data' => [
                '*' => $modelJsonStructure
            ],
            'first_page_url',
            'from',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
        ];
    }
}
