<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserStatusRequest;
use App\Models\Conversation;
use App\Models\UserStatus;
use App\Http\Requests\StatusRequest;
use Carbon\Carbon;

class UserStatusController extends Controller
{

    public function updateLastOnline()
    {
        UserStatus::query()->updateOrCreate(['user_id' => auth()->id()],[
            'last_online_at' => Carbon::now()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserStatusRequest  $request
     * @param  \App\Models\UserStatus  $userStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserStatusRequest $request)
    {
        $cons = Conversation::query()->whereIn('id', $request->cons)->get();
        $data=[];
        foreach ($cons as $con){
            $data[]=[
              'cons_id'=>$con->id,
              'status'=>$con->status()
            ];
        }
        return response()->json($data);
    }

}
