<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorldControllerTest extends TestCase
{
    /**
     * A basic feature test get countries.
     *
     * @return void
     */
    public function test_get_countries()
    {
        $response = $this->get('api/v1/world/countries?term=ecu');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'countries' => [
                    '*' => [
                        'id',
                        'name',
                        'iso3',
                        'numeric_code',
                        'iso2',
                        'phonecode',
                        'capital',
                        'currency',
                        'currency_name',
                        'currency_symbol',
                        'tld',
                        'native',
                        'region',
                        'subregion',
                        'timezones',
                        'translations',
                        'latitude',
                        'longitude',
                        'emoji',
                        'emojiU',
                        'flag',
                        'wikiDataId',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/world/countries.success.json');
    }

    /**
     * A basic feature test get states.
     *
     * @return void
     */
    public function test_get_states()
    {
        $response = $this->get('api/v1/world/states?term=azua');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'states' => [
                    '*' => [
                        'id',
                        'name',
                        'country_code',
                        'fips_code',
                        'iso2',
                        'type',
                        'latitude',
                        'longitude',
                        'flag',
                        'wikiDataId',
                        'country_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/world/states.success.json');
    }

    /**
     * A basic feature test get cities.
     *
     * @return void
     */
    public function test_get_cities()
    {
        $response = $this->get('api/v1/world/cities?term=cuenc');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'msg',
            'data' => [
                'cities' => [
                    '*' => [
                        'id',
                        'name',
                        'state_code',
                        'country_code',
                        'latitude',
                        'longitude',
                        'flag',
                        'wikiDataId',
                        'country_id',
                        'state_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]
        ]);

        $this->jsonDocStorage($response, 'api/v1/world/cities.success.json');
    }
}
