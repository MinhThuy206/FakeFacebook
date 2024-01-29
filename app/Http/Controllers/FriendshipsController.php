<?php

namespace App\Http\Controllers;

use App\Http\Requests\Friend\StoreAddFriendHistoryRequest;
use App\Models\AddFriendHistory;

//use App\Http\Requests\UpdateFriendshipsRequest;

class FriendshipsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddFriendHistoryRequest $request)
    {
        $friendship = AddFriendHistory::create([
            'user_id1' => auth()->id(),
            'user_id2' => $request->user_id2,
        ]);

        if(!$friendship){
            return response()->json(['message' => 'not exist'],422);
        }
        return response()->json($friendship, 200);
    }

    public function acceptFriend(AddFriendHistory $friendships){
        $friendships -> accept();

        return response()->json(['message' => 'Friend request accepted successfully', $friendships]);
    }

    public function rejectFriend(AddFriendHistory $friendships){
        $friendships -> reject();
        return response()->json(['message' => 'Friend request rejected successfully', $friendships]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AddFriendHistory $friendships)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddFriendHistory $friendships)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFriendshipsRequest $request, AddFriendHistory $friendships)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddFriendHistory $friendships)
    {
        $friendships->delete();

        return response()->json(['message' => 'Friendship deleted successfully']);
    }
}
