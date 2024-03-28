<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreConservationRequest;
use App\Http\Requests\Message\UpdateConservationRequest;
use App\Models\Conservation;
use App\Models\User;
use App\Models\UserInConservation;

class ConservationController extends Controller
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
     * Display the specified resource.
     */
    public function show(Conservation $conservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conservation $conservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConservationRequest $request, Conservation $conservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conservation $conservation)
    {
        //
    }
}
