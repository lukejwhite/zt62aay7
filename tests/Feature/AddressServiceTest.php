<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testGetAddressesByPostcode()
    {
        $postcode = 'B3 3A';

        // Mock the HTTP response
        Http::fake([
            '*' => Http::response([
                [
                    'uprn' => 10096964998,
                    'street' => '123 Main Steet',
                    'city' => 'Anytown',
                    'state' => 'Some County',
                    'postcode' => $postcode,
                ],
                [
                    'uprn' => 10096961124,
                    'street' => '456 Elm St',
                    'city' => 'Othertown',
                    'state' => 'Some County',
                    'postcode' => $postcode,
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/addresses?postcode=' . $postcode);

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['postcode' => $postcode])
            ->assertJsonStructure([
                '*' => [
                    'uprn',
                    'street',
                    'city',
                    'county',
                    'postcode',
                ],
            ]);
    }
}
