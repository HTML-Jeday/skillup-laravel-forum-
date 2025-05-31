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
     * Test failed registration scenarios
     *
     * @return void
     */
    public function testRegistrationFailed() {
        $response = $this->post('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->post('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->post('/register', [
            'name' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->post('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
            'password' => $password = '1'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->post('/register', [
            'email' => $email = '1',
            'name' => substr(md5(time()), 0, 10),
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->post('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => 1,
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
