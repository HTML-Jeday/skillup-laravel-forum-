<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class UserTest extends TestCase {

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function RegistrationSuccess() {

        $response = $this->postJson('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
            'password' => $password = substr(md5(time()), 0, 6)
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson('/login', [
            'email' => $email,
            'password' => $password
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    // parol name
    // parol email
    // name  email
    //
    //
    //

    public function RegistrationFailed() {

        $reponse = $this->postJson('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $reponse = $this->postJson('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $reponse = $this->postJson('/register', [
            'name' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $reponse = $this->postJson('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => substr(md5(time()), 0, 10),
            'password' => $password = '1'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $reponse = $this->postJson('/register', [
            'email' => $email = '1',
            'name' => substr(md5(time()), 0, 10),
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $reponse = $this->postJson('/register', [
            'email' => $email = substr(md5(time()), 0, 10) . '@google.com',
            'name' => 1,
            'password' => $password = substr(md5(time()), 0, 6)
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
