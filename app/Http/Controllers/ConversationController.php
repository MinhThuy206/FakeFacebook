<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\ConversationRequest;
use App\Http\Requests\Message\StoreConversationRequest;
use App\Models\Conversation;
use App\Models\User;
use App\Models\UserInConversation;

class ConversationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationRequest $request)
    {
         $cons = Conversation::create([
             'name' => $request -> name,
         ]);
         $users = User::query()->whereIn('id',$request->users)->get();
         foreach ($users as $user) {
             UserInConversation::query()->insert([
                 'cons_id' => $cons->id,
                 'user_id' => $user->id,
                 'admin' => auth()->id() == $user->id
             ]);
         }
    }

    /**
     * Filter Conversations.
     */
    public function filterConversations(ConversationRequest $request)
    {
        $conversations = Conversation::query()->whereHas('users', function ($q) {
            $q->where('users.id', '=', auth()->id());
        });

        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "asc";
        }

        $conversations = $conversations->orderBy($request->orderBy, $request->order)
            ->paginate($request->size ?? 10,'*',
                'page', $request->page ?? 0);
        $response = [
            "data" => array(),
            "current_page" => $conversations->currentPage(),
            "last_page" => $conversations->lastPage(),
            "per_page" => $conversations->perPage(),
            "total" => $conversations->total()
        ];

        foreach($conversations as $conversation){
            $response['data'][] = $conversation -> toArray();
        }
        return response()->json($response);
    }

}
