<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\ConservationRequest;
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
    public function filterConservations(ConservationRequest $request)
    {
        $conservations = Conservation::query()->whereHas('users', function ($q) {
            $q->where('users.id', '=', auth()->id());
        });

        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "desc";
        }

        $conservations = $conservations->orderBy($request->orderBy, $request->order)
            ->paginate($request->size ?? 10,'*',
                'page', $request->page ?? 0);
        $response = [
            "data" => array(),
            "current_page" => $conservations->currentPage(),
            "last_page" => $conservations->lastPage(),
            "per_page" => $conservations->perPage(),
            "total" => $conservations->total()
        ];

        foreach($conservations as $conservation){
            $response['data'][] = $conservation -> toArray();
        }
        return response()->json($response);
    }

}
