<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Conservation;
use App\Models\Message;
use App\Models\User;
use App\Models\UserInConservation;

class MessageController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $message = Message::create([
            'userTo' => $request->userTo,
            'message' => $request->get('message'),
        ]);
        broadcast(new MessageSent($request->get('message'), $request->userTo));
        if (!$message) {
            return response()->json(['message' => 'not exist'], 422);
        }
        return response()->json($message);
    }

    /**
     * Display the specified resource.
     */
    public function show($message)
    {
        $message = Message::query()->findOrFail($message);
        return response()->json($message->toArray());
    }

    public function filterMessage(MessageRequest $request, $userTo)
    {
        $userFrom = auth()->user()->id;
        $messages = Message::query();

        $messages->where(function ($query) use ($userTo, $userFrom) {
            $query->where('userFrom', $userFrom)
                ->where('userTo', $userTo)
                ->orWhere(function ($query) use ($userTo, $userFrom) {
                    $query->where('userFrom', $userTo)
                        ->where('userTo', $userFrom);
                });
        });

        if ($request->has('user_id'))
            $messages = $messages->where("user_id", "==", $request->user_id);
        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "asc";
        }

        $messages = $messages->orderBy($request->orderBy, $request->order);

        $response = [
            "data" => array(),
        ];

        foreach ($messages->get() as $message) {
            $response['data'][] = $message->toArray();
        }
        return response()->json($response);
    }


}
