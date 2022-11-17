<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test login.
     *
     * @return void
     */
    public function test_login()
    {
        $user = $this->getUser();

        $response = $this->post('api/v1/login', [
            'email'     => $user->email,
            'password'  => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'phone',
                    'identification',
                    'birthday',
                    'age',
                    'role',
                    'terms_conditions',
                    'privacy_policies',
                    'city_id',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/auth/login.success.json');
    }
}
