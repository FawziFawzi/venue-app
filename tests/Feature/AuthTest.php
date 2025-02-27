<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        // Assert the response status is 201 (Created)
        $response->assertStatus(201);

        // Assert the user is in the database
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@email.com',
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
        $user = User::create([
            'name' => 'mary',
            'email' => 'mary@email.com',
            'password' => Hash::make('password')
        ]);

//        Login data
        $loginData = [
            'email' => 'mary@email.com',
            'password' => 'password'
        ];

        // Send POST request to the login endpoint
        $response = $this->postJson('/api/login', $loginData);

        // Assert the response status is 202 ( Accepted )
        $response->assertStatus(202);

        // Assert the response contains token
        $response->assertJsonStructure([
            'token'
        ]);
    }

        //// Test Login Validation///
    /** @test */
    public function login_requires_valid_credentials()
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
        $response->assertJsonValidationErrors('email');
    }

}
