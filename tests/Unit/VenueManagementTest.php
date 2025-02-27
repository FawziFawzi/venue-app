<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class VenueManagementTest extends TestCase
{
    use RefreshDatabase;

    // List All Venues test
    /** @test */
    public function test_list_all_venues()
    {
        //Creating a user and generating a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create fake venues
        $venue1 = Venue::factory()->create(['name' => 'Venue 1']);
        $venue1 = Venue::factory()->create(['name' => 'Venue 2']);

        //Call the endpoint to get all venues
        $response = $this->getJson('/api/venues');
        // Assert the response has status of 200
        $response->assertStatus(200);

        //Assert the response has names of Venues
        $response->assertJson(['venues'=>[
            [
                'name'=>'Venue 1',
            ],
            [
                'name'=>'Venue 2',
            ]
        ]]);
    }

    // Create a venue test
    /** @test */
    public function test_can_create_a_venue()
    {
        
    }
}
