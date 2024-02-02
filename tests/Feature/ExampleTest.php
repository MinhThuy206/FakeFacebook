<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AddFriendHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
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

        auth()->loginUsingId($user1->id);

        $friend1 = AddFriendHistory::query()->create([
            'user_id2' => $user2 -> id,
        ]);

        DB::enableQueryLog();
        $x = $user2 -> pendingFriend() -> where('users.id','=', auth()->id()) -> get();
        print_r($x -> toArray());
        print_r(DB::getQueryLog());
    }


}
