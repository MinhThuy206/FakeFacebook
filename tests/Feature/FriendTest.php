<?php

namespace Tests\Feature;

use App\Enums\FriendshipStatus;
use App\Models\AddFriendHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class FriendTest extends TestCase
{
    use RefreshDatabase;
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

        print("Danh sach nhung nguoi user1 gui ket ban");
        $friends = $user1->pendingFriend()->get();
        $array = array();
        foreach ($friends as $friend){
            $array[]= $friend -> toArray();
        }
        print_r($array);

        print("Danh sach nhung nguoi gui ket ban cho user2");
        //List user add friend
        auth()->loginUsingId($user2->id);
        $listFriends = $user2 -> listAddFriends() -> get();
        $array = array();
        foreach ($listFriends as $friend){
            $array[]= $friend -> toArray();
        }
        print_r($array);

        $this->assertTrue(true);

        // accept friend
        auth()->loginUsingId($user1->id);
        $friend1 -> accept();
        $friend2 -> accept();
        $friends = $user1->friends()->get();
        $array = array();
        foreach ($friends as $friend){
            $array[]= $friend -> toArray();
        }

        print("danh sach nhung nguoi da ket ban voi user1");
        print_r($array);

        print("User1");
        print_r($user1->toArray());
    }
}
