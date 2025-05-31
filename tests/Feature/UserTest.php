<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class UserTest extends TestCase {
    use RefreshDatabase;

    /**
     * Test successful user registration and login
     *
     * @return void
     */
    public function testRegistrationSuccess() {
        $email = substr(md5(time()), 0, 10) . '@google.com';
        $password = substr(md5(time()), 0, 6);
        
        $response = $this->post('/register', [
            'email' => $email,
            'name' => substr(md5(time()), 0, 10),
            'password' => $password
        ]);

        $response->assertStatus(302); // Expect redirect
        $response->assertSessionHas('success', 'User has been created successfully');

        // Test that we can login with the new credentials
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password
        ]);

        $response->assertStatus(302); // Expect redirect
        $response->assertSessionHas('success', 'You have been logged in successfully');
    }

    /**
     * Test failed registration scenarios with JSON requests
     *
     * @return void
     */
    public function testRegistrationFailed() {
        // Test missing password
        $response = $this->postJson('/register', [
            'email' => substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['password']);

        // Test missing name
        $response = $this->postJson('/register', [
            'email' => substr(md5(time()), 0, 10) . '@google.com',
            'password' => substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['name']);

        // Test missing email
        $response = $this->postJson('/register', [
            'name' => substr(md5(time()), 0, 10),
            'password' => substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['email']);

        // Test password too short
        $response = $this->postJson('/register', [
            'email' => substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
            'password' => '1'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['password']);

        // Test invalid email
        $response = $this->postJson('/register', [
            'email' => '1',
            'name' => substr(md5(time()), 0, 10),
            'password' => substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['email']);

        // Test invalid name type
        $response = $this->postJson('/register', [
            'email' => substr(md5(time()), 0, 10) . '@google.com',
            'name' => 1,
            'password' => substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['name']);
    }
}
