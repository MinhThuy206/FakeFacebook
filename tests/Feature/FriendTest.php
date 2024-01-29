<?php

namespace Tests\Feature;

use App\Enums\FriendshipStatus;
use App\Models\AddFriendHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user1 = User::query()->create([
            'name' => 'Thuy',
            'phone' => '0379371432',
            'email' => 'bichthuy@gmail.com',
            'password' => 'Thuy01!!!',
        ]);

        $user2 = User::query()->create([
            'name' => 'PhanHung',
            'phone' => '0379371431',
            'email' => 'phanhung@gmail.com',
            'password' => 'Thuy01!!!',
        ]);


        $user3 = User::query()->create([
            'name' => 'KimNgan',
            'phone' => '0379371434',
            'email' => 'kimngan@gmail.com',
            'password' => 'Thuy01!!!',
        ]);

        auth()->loginUsingId($user1->id);

        $friend1 = AddFriendHistory::query()->create([
            'user_id2' => $user2 -> id,
        ]);

        $friend2 = AddFriendHistory::query()->create([
            'user_id2' => $user3 -> id,
        ]);

        $friends = $user1->pendingFriend()->get();
        $array = array();
        foreach ($friends as $friend){
            $array[]= $friend -> toArray();
        }
        print_r($array);

        $this->assertTrue(true);

        // accept friend
        $friend1 -> accept();
        $friend2 -> accept();
        $friends = $user1->friends()->get();
        $array = array();
        foreach ($friends as $friend){
            $array[]= $friend -> toArray();
        }
        print_r($array);
    }
}
