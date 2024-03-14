<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function form_messenger($username)
    {
        $user = User::where('username', $username)->first();
        $toUser=$user->id;
        return view('page.auth.messenger', compact(['user', 'toUser']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $message = Message::create([
            'userTo' => $request->userTo,
            'message' => $request->get('message'),
        ]);
        broadcast(new MessageSent($request->get('message'),$request->userTo));
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

        foreach($messages -> get() as $message){
            $response['data'][] = $message -> toArray();
        }
        return response() -> json($response);
    }

    public function filterUserMessage()
    {
        $loggedInUserId = auth()->user()->id;

        $userIdsFromMessagesToLoggedInUser = Message::where('userTo', $loggedInUserId)
            ->pluck('userFrom')
            ->unique();

        // Lấy ra các user_id của những người mà người đang đăng nhập đã nhắn tin
        $userIdsFromMessagesFromLoggedInUser = Message::where('userFrom', $loggedInUserId)
            ->pluck('userTo')
            ->unique();

        // Kết hợp và loại bỏ id của người đang đăng nhập khỏi danh sách
        $relatedUserIds = $userIdsFromMessagesToLoggedInUser->merge($userIdsFromMessagesFromLoggedInUser)
            ->reject(function ($userId) use ($loggedInUserId) {
                return $userId == $loggedInUserId;
            });

        // Lấy thông tin chi tiết của các người dùng liên quan
        $relatedUsers = User::whereIn('id', $relatedUserIds)
            ->orderByDesc(function ($query) use ($loggedInUserId) {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('userFrom', 'users.id')
                    ->where('userTo', $loggedInUserId)
                    ->orWhere(function ($query) use ($loggedInUserId) {
                        $query->whereColumn('userTo', 'users.id')
                            ->where('userFrom', $loggedInUserId);
                    })
                    ->latest()
                    ->limit(1);
            })
            ->get();

        // Trả về danh sách các người dùng liên quan
        return $relatedUsers;
    }
}
