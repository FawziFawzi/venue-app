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


    //Test Un authorized users
    public function test_unauthorized_access_to_venues()
    {
        //Call the endpoint to get all venues with no authorization
        $response = $this->getJson('/api/venues');

        // Assert the response has status of 401 ( Unauthorized )
        $response->assertStatus(401);

    }
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
        // Create a user and a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        // Validation data
        $venueData = [
            'name' => 'Venue 1',
            'location' => 'galaa st 2 ns',
            'capacity' => '23',
            'user_id' => $user->id,
        ];

            // Sending  a POST request to the endpoint
        $response = $this->postJson('/api/venues', $venueData);

        //Assert the response status is 201 ( Created )
        $response->assertStatus(201);

        //Assert the message response
        $this->assertDatabaseHas('venues', [
            'name' => 'Venue 1',
            'location' => 'galaa st 2 ns',
        ]);
    }

    // Test invalid credentials while creating a venue
    /**@test*/
    public function test_invalid_data_to_create_a_venue()
    {
        // Create a user and a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        // Validation data
        $invalidData = [
            'name' => 'Venue 1',
            'location' => 'galaa st 2 ns',//No Capacity
            'user_id' => $user->id,
        ];

        // Sending  a POST request to the endpoint
        $response = $this->postJson('/api/venues', $invalidData);

        //Assert the response status is 422 ( Unprocessable Content )
        $response->assertStatus(422);

        //Assert the message response
        $response->assertJsonValidationErrors(['capacity']);
    }

    //Test Update a venue
    public function test_can_update_a_venue(){
        // Create a user and a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        //Create a venue
        $venue = Venue::factory()->create(['name' => 'Venue 1']);
        // Validation data
        $updatedData = [
            'name' => 'Venue new',
            'location' => 'new location',
            'capacity' => '230',
            'user_id' => $user->id,
        ];

        // Sending  a Put request to the endpoint
        $response = $this->putJson('/api/venues/'.$venue->id, $updatedData);

        //Assert the response status is 200 ( Ok )
        $response->assertStatus(200);

        //Assert the message response
        $this->assertDatabaseHas('venues', [
            'name' => 'Venue new',
            'location' => 'new location',
        ]);
    }

    //Test Update invalid data to a Venue
    public function test_invalid_validation_to_update_a_venue()
    {
        // Create a user and a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        //Create a venue
        $venue = Venue::factory()->create(['name' => 'Venue 1']);
        // Validation data
        $invalidData = [
            'name' => 'Venue new',
            'location' => 'new location',
            'capacity' => '-1',//negative capacity
        ];

        // Sending  a Put request to the endpoint
        $response = $this->putJson('/api/venues/'.$venue->id, $invalidData);

        //Assert the response status is 422 ( Unprocessable Content )
        $response->assertStatus(422);

        //Assert the message response
        $response->assertJsonValidationErrors(['capacity']);
    }

    //Test update non existing venue
    public function test_update_non_existing_venue()
    {
        // Create a user and a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $nonExsistingId =123458;

        // Validation data
        $updatedData = [
            'name' => 'Venue new',
            'location' => 'new location',
            'capacity' => '230',
            'user_id' => $user->id,
        ];

        // Sending  a Put request to the endpoint
        $response = $this->putJson('/api/venues/'.$nonExsistingId, $updatedData);

        //Assert the response status is 404 ( Not found )
        $response->assertStatus(404);
    }

    //Test delete a venue
    public function test_can_delete_a_venue(){
        // Create a user and a token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        //Create a venue
        $venue = Venue::factory()->create(['name' => 'Venue to delete']);

        // Sending  a DELETE request to the endpoint
        $response = $this->delete('/api/venues/'.$venue->id);
        //Assert the response status is 422 ( No content )
        $response->assertStatus(204);

        // Assert the venue doesn't exist anymore in the database
        $this->assertDatabaseMissing('venues', [
            'name' => 'Venue to delete'
        ]);
    }
}
