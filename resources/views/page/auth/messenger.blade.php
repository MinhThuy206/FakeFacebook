@extends('layout.layout')
@section('title', 'Profile')
@section('content')

<style>
    .card-message {
        clear: both;
        margin-top: 16px;
        position: relative;
    }

    .message-text {
        background-color: #e4e6eb;
        border-radius: 18px;
        color: #333;
        margin: 0 8px;
        padding: 12px 16px;
        max-width: 80%;
        font-size: 12px;
        margin-bottom: 8px;
        font-weight: 400;
    }

    .card-message.sender .message-text {
        background-color: #007bff;
        color: #fff;
        float: right;
    }

    .card-message.receiver .message-text {
        background-color: #e4e6eb;
        float: left;
    }

    .message-header {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #fff;
        border-bottom: 1px solid #ccc;
        position: fixed; /* Thiết lập vị trí cố định */
        top: 56px; /* Đặt header ở phía trên cùng của trình duyệt */
        width: 100%; /* Chiều rộng 100% để nó căng đầy toàn bộ phần header */
        z-index: 1000; /* Đảm bảo header hiển thị trên tất cả các phần khác */
        height: 60px
    }

    .profile-picture {
        margin-right: 10px;
    }

    .profile-picture img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .username {
        font-weight: 500;
        font-size: 24px;
    }

    .avatar-img {
        border-radius: 50%;
    }

    .message-content {
        margin-top: 20px;
        height: 589px; /* hoặc bất kỳ kích thước cố định nào bạn muốn */
        overflow-y: auto; /* Kích hoạt thanh cuộn dọc */
    }

    .message-input {
        width: 100%;
        bottom: 0; /* Đặt phần dưới của message-input ở phía dưới cùng của cửa sổ trình duyệt */
        background-color: #ffffff; /* Màu nền cho phần nhập tin nhắn */
        padding: 10px; /* Thêm padding cho đẹp */
        z-index: 1000; /* Đảm bảo nó hiển thị trên tất cả các phần khác */
    }

    .body{
        background-color: #fff;
        padding: 0;
        border: 1px solid #ccc;
    }

    .form-group {
        display: flex;
        flex-grow: 1;
    }

    .form-control {
        flex-grow: 1;
    }

    .send-button {
        border-radius: 27px; /* Để có góc bo phải cho nút gửi tin nhắn */
        border: none;
        background-color: #007bff; /* Màu nền của nút */
        color: #fff; /* Màu chữ của nút */
        cursor: pointer;
        margin-left: 10px;
    }

    /* Định dạng nút gửi tin nhắn */
    .send-button i {
        font-size: 24px; /* Kích thước của biểu tượng gửi */
    }

    .col-md-3 {
        padding: 0;
    }

    .card-header {
        background-color: #fff;
        padding: 10px;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 27px;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
    }

    .title-select {
        display: flex;
        margin-top: 10px;
    }

    .mess {
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 27px;
        background-color: #EBF5FF;
        color: #0064D1;
        font-weight: 600;
    }

    .public {
        cursor: pointer;
        padding: 5px 10px;
        margin: 0 15px;
        border-radius: 27px;
    }

    .mess:hover, .public:hover {
        background-color: #f0f2f5;
    }

    .list-chat {
        overflow-y: auto;
        max-height: 527px;
    }

    .chat-user {
        padding: 10px;
        background-color: #fff;
        border-top: 1px solid #ced4da;
        margin-bottom: 10px;
    }

    /* Optional: Customize scrollbar */
    .list-chat::-webkit-scrollbar {
        width: 8px;
    }

    .list-chat::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .list-chat::-webkit-scrollbar-thumb:hover {
        background-color: #b3b3b3;
    }

    .user {
        display: flex;
        align-items: center;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    .user:hover{
        background-color: #e4e6eb;
    }

    .avatar img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .username {
        font-size: 15px;
        font-weight: 500;
    }

</style>

<div class="container-fluid" style="margin-top: 56px; display: flex; flex-direction: column; height: auto">
    <div class="row" style="flex: 1;">
        <div class="col-md-3">
            <div class="card-header">
                <div class="title">Đoạn chat</div>
                <form class="d-flex me-auto">
                    <input class="form-control me-2" type="search" placeholder="Tìm kiếm trên Messenger" aria-label="Search" style="border-radius: 27px">
                </form>
                <div class="title-select">
                    <div class="mess">Hộp Thư</div>
                    <div class="public">Cộng đồng</div>
                </div>
            </div>
            <div class="list-chat">
                <div class="chat-user" id="chat-user">

                </div>
            </div>
        </div>


        <div class="col-md-9 body">
            <div class="message-header">
                <div class="profile-picture">
                    @if($user['avatar_url'] == null)
                        <img id="avatar-img" class="avatar-img" src="../image/avatar-trang.jpg" alt="">
                    @else
                        <img id="avatar-img" class="avatar-img" src="{{ asset($user['avatar_url']) }}" alt="...">
                    @endif
                </div>
                <div>
                    <div class="username">{{ $user['name'] }}</div>
                </div>

            </div>
            <div class="message-container">
                <div class="message-content" id="messageBody">
                    <div class="messages" id="message"></div>
                </div>
                <div class="message-input">
                    <form id="messageForm">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" placeholder="Nhập tin nhắn..." id="messageInput" name="message">
                            <button class="send-button" style="padding: 4px 13px" type="submit"><i class="material-icons">send</i></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
    <script>
        const userId = {{auth()->user()->id}};
        const userTo = {{$toUser}};
        var userName = "{{$user->username}}";
        var name = "{{$user->name}}"
    </script>
    <script src="{{asset('/js/message.js')}}"></script>
    <script>
        $(document).ready(function () {
            getData({
                'user_id': {{auth()->user()->id}},
                'orderBy': 'created_at',
                'order': 'DESC',
            })
        })
    </script>
@endsection
