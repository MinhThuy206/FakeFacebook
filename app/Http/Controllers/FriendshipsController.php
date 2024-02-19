<?php

namespace App\Http\Controllers;

use App\Enums\FriendshipStatus;
use App\Http\Requests\Friend\StoreAddFriendHistoryRequest;
use App\Http\Requests\Post\FilterRequest;
use App\Models\AddFriendHistory;
use App\Models\Friend;
use App\Models\User;

class FriendshipsController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.friend.friends');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddFriendHistoryRequest $request)
    {
        $friendship = AddFriendHistory::create([
            'user_id2' => $request->user_id2,
        ]);

        if (!$friendship) {
            return response()->json(['message' => 'not exist'], 422);
        }
        return response()->json($friendship);
    }

    /**
     * Add friend and save to database.
     */
    public function acceptFriend($friendships)
    {
        if (auth()->id() == AddFriendHistory::query()->where('user_id1', $friendships)->first()->user_id2) {
            $friendships = AddFriendHistory::query()->where('user_id1',  $friendships) -> first();
            $friendships->update(['status' => FriendshipStatus::ACCEPTED]);
            Friend::query()->insert([[
                "user_id1" => $friendships->user_id1,
                "user_id2" => $friendships->user_id2
            ], [
                "user_id1" => $friendships->user_id2,
                "user_id2" => $friendships->user_id1
            ]]);
            return response()->json(['message' => 'Friend request accepted successfully', $friendships]);
        } else {
            return response()->json(['message' => 'Friend request accepted fail', $friendships]);
        }
    }

    public function rejectFriend($friendships)
    {
        if(auth()->id() == AddFriendHistory::query()->where('user_id1', $friendships) -> first()->user_id2){
            $friendships =  AddFriendHistory::query()->where('user_id1',  $friendships) -> first();
            $friendships->update(['status' => FriendshipStatus::REJECTED]);
            return response()->json(['message' => 'Friend request rejected successfully', $friendships]);
        }else{
            return response()->json(['message' => 'Friend request rejected fail', $friendships]);
        }
    }

    /**
     * filter list user.
     */
    public function filterUser(FilterRequest $request)
    {
        $users = User::query();
        $currentUser = auth()->id();

        $friendIds = Friend::where('user_id1', $currentUser)
            ->pluck('user_id2')
            ->toArray();

        $users->where('id', '!=', $currentUser);
        $users->whereNotIn('id', $friendIds);

        if ($request->has('user_id')) {
            $users->where('user_id', '=', $request->user_id);
        }

        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "desc";
        }

        $users = $users->orderBy($request->orderBy, $request->order)
            ->paginate($request->size ?? 10, '*',
                'page', $request->page ?? 0);

        $response = [
            "data" => array(),
            "current_page" => $users->currentPage(),
            "last_page" => $users->lastPage(),
            "per_page" => $users->perPage(),
            "total" => $users->total()
        ];

        foreach ($users->items() as $user) {
            $response['data'][] = $user->toArray();
        }
        return response()->json($response);
    }

    /**
     *  Filter list friend
     */
    public function filterFriend(FilterRequest $request)
    {
        $users = User::query();
        $currentUser = auth()->id();

        $friendIds = Friend::where('user_id1', $currentUser)
            ->pluck('user_id2')
            ->toArray();

        $users->where('id', '!=', $currentUser);
        $users->whereIn('id', $friendIds);

        if ($request->has('user_id')) {
            $users->where('user_id', '=', $request->user_id);
        }

        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "desc";
        }

        $users = $users->orderBy($request->orderBy, $request->order)
            ->paginate($request->size ?? 10, '*',
                'page', $request->page ?? 0);

        $response = [
            "data" => array(),
            "current_page" => $users->currentPage(),
            "last_page" => $users->lastPage(),
            "per_page" => $users->perPage(),
            "total" => $users->total()
        ];

        foreach ($users->items() as $user) {
            $response['data'][] = $user->toArray();
        }
        return response()->json($response);
    }

    /**
     * Remove request add friend.
     */
    public function deleteRequestAddFriend($user)
    {
        $friend = AddFriendHistory::where(function ($query) use ($user){
            $query -> where('user_id1','=',auth()->id())
                -> where('user_id2','=',$user);
        })->orWhere(function ($query) use ($user){
            $query -> where('user_id1','=', $user)
                -> where('user_id2','=',auth()->id());
        });

        $friend -> delete();
        return response()->json(['message' => 'Friendship deleted successfully']);
    }

    /**
     * Remove friend.
     */
    public function deleteFriend($user){
        $friend1 =Friend::query() -> where('user_id1', $user);
        $friend1->delete();

        $friend2 = Friend::query() -> where('user_id2', $user);
        $friend2->delete();
        return response()->json(['message' => 'Friendship deleted successfully']);
    }
}
