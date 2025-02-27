<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class AuthTest extends TestCase
{
    use RefreshDatabase;

    /* Registration Test */
    /** @test */
    public function a_user_can_register()
    {
        // Data for the new user
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        //Send a POST request to the registration endpoint
        $response = $this->postJson('/api/register', $userData);

        // Assert the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert the user is in the database
        $this->assertDatabaseHas('users', [
            'name' => $response['user']['name'],
            'email' => $response['user']['email'],
        ]);

    }
    //// Testing registration with invalid data
    /** @test */
    public function invalid_data_registration()
    {
        // Invalid data ( Password confirmation is missing )
        $invalidData = [
            'name' => 'John Doe',
            'email' => 'johndoe@email.com',
            'password' => 'password',
        ];

        //Send a POST request to the registration endpoint
        $response = $this->postJson('/api/register', $invalidData);

        // Assert the response status is 422 ( Unprocessable Content )
        $response->assertStatus(422);

        // Assert the response contains validation errors
        $response->assertJsonValidationErrors('password');
    }

    //// User login Tests ////
    /** @test */
    public function a_user_can_login()
    {
//        Create a user
        $user = User::factory()->create([
            'name' => 'Mary',
            'email' =>'mary@email.com',
            'password' => Hash::make('password')
        ]);

//        Login data
        $loginData = [
            'email' => 'mary@email.com',
            'password' => 'password'
        ];

        // Send POST request to the login endpoint
        $response = $this->postJson('/api/login', $loginData);

        // Assert the response status is 200 ( OK )
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User Login Successfully!'
            ]);

//        // Assert the response contains token
        $response->assertJsonStructure([
            'token'
        ]);

    }

    //// Test invalid credentials///
    /** @test */
    public function login_with_invalid_credentials()
    {
        //        Create a user
        $user = User::create([
            'name' => 'mary',
            'email' => 'mary@email.com',
            'password' => Hash::make('password')
        ]);

        // Invalid credentials for login
        $invalidData = [
            'email' => 'mary@email.com',
            'password' => 'passworddd'
        ];
        // Send POST request to the login endpoint
        $response = $this->postJson('/api/login', $invalidData);

        // Assert the response status is 422 ( Unprocessable Content )
        $response->assertStatus(422);

        // Assert the response contains validation errors
        $response->assertJsonValidationErrors('error');
    }
}
