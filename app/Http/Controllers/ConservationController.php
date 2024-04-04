<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreConservationRequest;
use App\Models\Conservation;
use App\Models\User;
use App\Models\UserInConservation;

class ConservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConservationRequest $request)
    {
         $cons = Conservation::create([
             'name' => $request -> name,
         ]);
         $users = User::query()->whereIn('id',$request->users)->get();
         foreach ($users as $user) {
             UserInConservation::query()->insert([
                 'cons_id' => $cons->id,
                 'user_id' => $user->id,
                 'admin' => auth()->id() == $user->id
             ]);
         }
    }

    /**
     * Filter Conservations.
     */
    public function filterConservations()
    {
        $conversations = Conservation::query()->whereHas('users', function ($q) {
            $q->where('users.id', '=', auth()->id());
        })->get();
        $response = [
            "data" => array(),
//            "current_page" => $conversations->currentPage(),
//            "last_page" => $conversations->lastPage(),
//            "per_page" => $conversations->perPage(),
//            "total" => $conversations->total()
        ];

        foreach($conversations as $conversation){
            $response['data'][] = $conversation -> toArray();
        }
        return response()->json($response);
    }

}
