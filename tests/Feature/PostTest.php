<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
    $user = User::query()->create([
        'name' => 'Thuy',
        'phone' => '0379371432',
        'email' => 'dothibichthuy_t65@hus.edu.vn',
        'password' => 'Thuy01!!!',
    ]);

    auth() ->loginUsingId($user -> id);
    $post = Post::query()->create([
        'content' => 'Thuy xinh gai',
    ]);

    $post = Post::query()->findOrFail($post -> id);
    print(json_encode($post));
    }
}
