<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void {

        parent::setUp();

        $this->userJsonStructure = [
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
        ];
    }

    /**
     * A basic feature test store.
     *
     * @return void
     */
    public function test_store()
    {
        $user = User::factory()->make();

        $response = $this->post('api/v1/register', [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'phone'                 => $user->phone,
            'identification'        => $user->identification,
            'birthday'              => $user->birthday,
            'password'              => 'Abc123%31*',
            'password_confirmation' => 'Abc123%31*',
            'terms_conditions'      => $user->terms_conditions,
            'privacy_policies'      => $user->privacy_policies,
            'city_id'               => $user->city_id,
            'terms_conditions'      => true,
            'privacy_policies'      => true,
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'token',
                'user' => $this->userJsonStructure
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/user/store.success.json');
    }

    /**
     * A basic feature test show.
     *
     * @return void
     */
    public function test_show()
    {
        $response = $this->get('api/v1/profile', $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'user' => $this->userJsonStructure
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/user/show.success.json');
    }

    /**
     * A basic feature test update.
     *
     * @return void
     */
    public function test_update()
    {
        $user = $this->getUser();

        $form = [
            'name'                  => $this->faker->name(),
            'phone'                 => $user->phone,
            'birthday'              => $user->birthday,
            'current_password'      => 'password',
            'password'              => 'Abc123%31*',
            'password_confirmation' => 'Abc123%31*',
            'city_id'               => $user->city_id,
        ];

        $response = $this->put('api/v1/profile', $form, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'user' => $this->userJsonStructure
            ]
        ]);

        $this->resetPassword();
        $this->jsonDocStorage($response, 'api/v1/user/update.success.json');
    }

    /**
     * A basic feature test update password.
     *
     * @return void
     */
    public function test_update_password()
    {
        $form = [
            'current_password'      => 'password',
            'password'              => 'Abc123%31*',
            'password_confirmation' => 'Abc123%31*',
        ];

        $response = $this->put('api/v1/profile/update/password', $form, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'user' => $this->userJsonStructure
            ]
        ]);

        $this->resetPassword();
        $this->jsonDocStorage($response, 'api/v1/user/update-password.success.json');
    }

    /**
     * A basic feature test update terms and policies.
     *
     * @return void
     */
    public function test_update_terms_and_policies()
    {
        $form = [
            'terms_conditions'  => true,
            'privacy_policies'  => true,
        ];

        $response = $this->put('api/v1/profile/update/terms-and-policies', $form, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'user' => $this->userJsonStructure
            ]
        ]);

        $this->resetPassword();
        $this->jsonDocStorage($response, 'api/v1/user/update-terms-and-policies.success.json');
    }

    /**
     * A basic feature test delete account.
     *
     * @return void
     */
    public function test_delete_account()
    {
        $user = User::factory()->create();
        $token = $user->createToken('claroinsurance_app')->accessToken;
        $headers = ['Authorization' => 'Bearer '. $token];

        $form = [
            'password' => 'password',
        ];

        $response = $this->put('api/v1/profile/delete-account', $form, $headers);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
        ]);

        $this->jsonDocStorage($response, 'api/v1/user/delete-account.success.json');
    }
}
