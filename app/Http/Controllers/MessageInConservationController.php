<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\Message\StoreMessageInConservationRequest;
use App\Http\Requests\UpdateMessageInConservationRequest;
use App\Models\MessageInConservation;
use App\Models\UserInConservation;

class MessageInConservationController extends Controller
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
    public function store(StoreMessageInConservationRequest $request)
    {
        $cons = UserInConservation::query()->where('cons_id', $request->cons_id)
                                ->where('user_id', auth()->id())->first();;
        if(!$cons){
            return response()->json(['message' => 'not exist']);
        }else{
            $message = MessageInConservation::create([
                'cons_id' => $request->cons_id,
                'message' => $request->get('message'),
            ]);

//            die($message);

            if (!$message) {
                return response()->json(['message' => 'not exist'], 422);
            }else{
                $users = UserInConservation::query()->where('cons_id',$request->cons_id)->get();
//                die($users);
                foreach($users as $user){
                    broadcast(new MessageSent($request->get('message'),$user->id, auth()->user(),$request->cons_id));
                }
                return response()->json($message);
            }
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(MessageInConservation $messageInConservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageInConservation $messageInConservation)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageInConservation $messageInConservation)
    {
        //
    }
}
