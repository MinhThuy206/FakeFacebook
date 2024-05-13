<?php

namespace App\Listeners;

use App\Events\UserStatusEvent;
use BeyondCode\LaravelWebSockets\Events\ConnectionClosed;
use Carbon\Carbon;

class OnConnectionClosed
{
    public function handle(ConnectionClosed $event)
    {
//        info($connection->socketId);
//        info(auth()->id());
        $userId = $event->auth()->id(); // Giả sử bạn có thể lấy được ID của người dùng từ sự kiện
        $offlineTime = Carbon::now(); // Lấy thời gian hiện tại

        // Gửi thông điệp về trạng thái offline
        broadcast(new UserStatusEvent($userId, $offlineTime));
    }
}
