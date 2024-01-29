<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccountApiTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
//    public function test_example(): void
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }

    public function setup():void
    {
        parent::setUp();
        $user1 = User::query()->create([
            'name' => 'Thuy',
            'phone' => '0379371432',
            'email' => 'bichthuy@gmail.com',
            'password' => 'Thuy01!!!',
        ]);
    }

    public function test_register_fail()
    {
        $response = $this->json('post','/api/register', [
            'email'=> 'bichthuy@gmail.com',
            'name' => 'Thuy',
            'phone' => '0379371432',
            'password' => 'Thuy01!!!'
        ]);
        $response ->assertStatus(422);
    }

    public function test_register_success()
    {
        $response = $this->json('post','/api/register', [
            'email'=> 'phanhung@gmail.com',
            'name' => 'PhanHung',
            'phone' => '0379371431',
            'password' => 'Thuy01!!!'
        ]);
        $response ->assertStatus(200);
    }

    public function test_login_fail()
    {
        $response = $this->json('post','/api/login', [
            'email'=> 'phanhung@gmail.com',
            'password' => 'Thuy01!!'
        ]);
        $response ->assertStatus(422);

        $response = $this->json('post','/api/login', [
            'email'=> 'bichthuy@gmail.com',
            'password' => 'Thuy01!!'
        ]);
        $response ->assertStatus(403);
    }

    public function test_login_success()
    {
        $response = $this->json('post','/api/login', [
            'email'=> 'bichthuy@gmail.com',
            'password' => 'Thuy01!!!'
        ]);
        $response ->assertStatus(200);
    }
}
