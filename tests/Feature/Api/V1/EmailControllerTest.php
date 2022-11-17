<?php

namespace Tests\Feature\Api\V1;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void {

        parent::setUp();

        $this->emailJsonStructure = [
            'id',
            'subject',
            'to',
            'message',
            'status',
            'user_id',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * A basic feature test get emails.
     *
     * @return void
     */
    public function test_get_emails()
    {
        Email::factory()->count(2)->state([
            'user_id' => $this->getUser()->id,
        ])->create();

        $response = $this->get('api/v1/emails', $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'emails' => $this->getPaginationJsonStructure($this->emailJsonStructure)
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/email/emails.success.json');
    }


    /**
     * A basic feature test store.
     *
     * @return void
     */
    public function test_store()
    {
        $form = [
            'subject'   => $this->faker->word(),
            'to'        => $this->faker->safeEmail(),
            'message'   => $this->faker->text(200),
        ];

        $response = $this->post('api/v1/emails', $form, $this->getHeaders());
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'email' => $this->emailJsonStructure
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/email/store.success.json');
    }

    /**
     * A basic feature test show.
     *
     * @return void
     */
    public function test_show()
    {
        $email = Email::create([
            'subject'   => $this->faker->word(),
            'to'        => $this->faker->safeEmail(),
            'message'   => $this->faker->text(200),
            'user_id'   => $this->getUser()->id,
        ]);

        $response = $this->get('api/v1/emails/'.$email->id, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'email' => $this->emailJsonStructure
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/email/show.success.json');
    }
}
