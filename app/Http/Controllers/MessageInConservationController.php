<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Requests\Message\StoreMessageInConservationRequest;
use App\Models\Conservation;
use App\Models\MessageInConservation;
use App\Models\User;
use App\Models\UserInConservation;
use Illuminate\Support\Facades\DB;

class MessageInConservationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function form_messenger($identify)
    {
        $cons=null;
        if (is_numeric($identify)){
            $cons = Conservation::query()->whereHas('users', function ($q) {
                $q->where('users.id', '=', auth()->id());
            })->where('id','=',$identify)->first();
        } else {
            $user = User::where('username', $identify)->first();
            $cons = Conservation::query()->whereHas('users', function ($q) {
                $q->where('users.id', '=', auth()->id());
            })->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', '=', $user->id);
            })->where('two', true)->first();
            if (!$cons) {
                $cons = Conservation::create([
                    'name' => $user->name,
                    'avtGroup_id' => $user->avatar_id,
                    'two' => true
                ]);
                UserInConservation::query()->insert([
                    'cons_id' => $cons->id,
                    'user_id' => $user->id,
                    'admin' => true
                ]);
                UserInConservation::query()->insert([
                    'cons_id' => $cons->id,
                    'user_id' => auth()->id(),
                    'admin' => true
                ]);
            }
        }
        return view('page.auth.messenger', compact(['cons']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageInConservationRequest $request)
    {
        $cons = UserInConservation::query()->where('cons_id', $request->cons_id)
            ->where('user_id', auth()->id())->first();
        if (!$cons) {
            return response()->json(['message' => 'not exist']);
        } else {
            $message = MessageInConservation::create([
                'cons_id' => $request->cons_id,
                'message' => $request->get('message'),
            ]);
            if (!$message) {
                return response()->json(['message' => 'not exist'], 422);
            } else {
                $users = UserInConservation::query()->where('cons_id', $request->cons_id)
                    ->where('user_id','!=',auth()->id())->get();
                foreach ($users as $user) {
                    broadcast(new MessageSent($message, $user->user_id));
                }
                return response()->json($message);
            }
        }
    }

    public function getMessageInConservation(MessageRequest $request,$cons_id)
    {
        Conservation::query()->where("id","=",$cons_id)->firstOrFail();
        $messages = MessageInConservation::query()->where('cons_id', '=', $cons_id);
        if (!$request->has('orderBy')) {
            $request->orderBy = "created_at";
        }
        if (!$request->has('order')) {
            $request->order = "desc";
        }

        $messages = $messages->orderBy($request->orderBy, $request->order)
            ->paginate($request->size ?? 10,'*',
                'page', $request->page ?? 0);

        $response = [
            "data" => array(),
            "current_page" => $messages->currentPage(),
            "last_page" => $messages->lastPage(),
            "per_page" => $messages->perPage(),
            "total" => $messages->total()
        ];

        foreach($messages -> items() as $messages){
            $response['data'][] = $messages-> toArray();
        }
        return response() -> json($response);
    }

}
