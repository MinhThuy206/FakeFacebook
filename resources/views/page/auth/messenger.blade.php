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
            margin: 0 8px 8px 0px;
            padding: 12px 16px;
            max-width: 80%;
            font-size: 12px;
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

        .card-message.receiver {
            display: flex;
            align-items: flex-start; /* Đảm bảo các phần tử con được căn chỉnh theo chiều dọc */
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

        .profile-picture img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            align-items: center; /* Căn chỉnh các phần tử con theo chiều dọc */
        }

        .chatname {
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

        .body {
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
            margin-right: 9rem;
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
            padding: 8px;
            cursor: pointer;
            border-radius: 5px;
        }

        .user:hover {
            background-color: #e4e6eb;
        }

        .avatar img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .username {
            font-size: 15px;
            font-weight: 500;
        }

        .message-date {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding: 5px 10px;
            background-color: #f0f2f5;
            border-radius: 5px;
        }

        .hidden {
            display: none;
        }

        /* CSS cho overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2); /* Màu nền mờ */
            z-index: 999; /* Đảm bảo nằm trên mọi phần tử khác */
            display: none; /* Mặc định ẩn overlay */
        }

        /* CSS cho modal */
        .modal {
            position: fixed;
            top: 50%; /* Đặt phần tử ở giữa theo chiều dọc */
            left: 50%; /* Đặt phần tử ở giữa theo chiều ngang */
            transform: translate(-50%, -50%); /* Dịch chuyển modal để nó được căn giữa trang */
            background-color: white;
            z-index: 1000; /* Đảm bảo nằm trên overlay */
            padding: 20px;
            border-radius: 5px;
            display: none; /* Mặc định ẩn modal */
            max-width: 50%; /* Đặt chiều rộng tối đa của modal */
            max-height: 80%; /* Đặt chiều cao tối đa của modal */
            overflow: auto; /* Cho phép cuộn nếu nội dung quá lớn */
        }

        .card-message.receiver {
            display: flex; /* Sử dụng flexbox để căn chỉnh các phần tử con */
            align-items: center; /* Căn chỉnh các phần tử con theo chiều dọc */
        }

        .username-container {
            opacity: 0; /* Ẩn tên người dùng ban đầu */
            transition: opacity 0.3s ease; /* Hiệu ứng chuyển đổi khi hiển thị tên người dùng */
        }

        .name-receiver {
            background-color: rgba(0, 0, 0, 0.8); /* Màu nền của phần tên người dùng */
            color: #fff; /* Màu chữ của tên người dùng */
            padding: 5px 10px; /* Khoảng cách giữa văn bản và biên của phần tên người dùng */
            border-radius: 5px; /* Đường viền cong của phần tên người dùng */
        }

        .card-message.receiver:hover .username-container {
            opacity: 1; /* Hiển thị tên người dùng khi hover vào card-message */
        }



    </style>

    <div class="container-fluid" style="margin-top: 56px; display: flex; flex-direction: column; height: auto">
        <div class="row" style="flex: 1;">
            <div class="col-md-3">
                <div class="card-header">
                    <div style="display: flex; align-items: center;">
                        <div class="title">Đoạn chat</div>
                        <div id="chatMessage" class="hidden">Tạo nhóm</div>
                        <div style="cursor: pointer; margin-right: 5px;" id="addIcon" class="conservations">
                            <button class="create-group"><i class="material-icons" style="margin-right: 3px;">add</i></button>
                            <!-- Thay "add" bằng class icon của bạn -->
                        </div>

                    </div>
                    <form class="d-flex me-auto">
                        <input class="form-control me-2" type="search" placeholder="Tìm kiếm trên Messenger"
                               aria-label="Search" style="border-radius: 27px">
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
            @if($cons != null)
                @php($cons = $cons->toArray())
                <div class="col-md-9 body" >
                    <div class="message-header">
                        <div class="profile-picture">
                            <img id="avatar-img" class="avatar-img" src="../{{$cons['avatar_url']}}" alt="...">
                        </div>
                        <div>
                            <div class="chatname">{{ $cons['name'] }}</div>
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
                                    <input class="form-control" placeholder="Nhập tin nhắn..." id="messageInput"
                                           name="message">
                                    <button class="send-button" style="padding: 4px 13px" type="submit"><i
                                            class="material-icons">send</i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tạo nhóm</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="createGroupForm">
                    <input type="text" class="groupName" id="groupName" placeholder="Nhập tên nhóm...">
                    <div class="cardUser">
                        <div class="listUser" id="listUser"></div>
                    </div>
                    <button type="submit">Tạo nhóm</button>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script>
        const userId = {{auth()->user()->id}};
        let consId = {{$cons['id']}};
    </script>

    <script src="{{asset('/js/message.js')}}"></script>

    <script>
        // JavaScript để giữ thanh cuộn luôn ở dưới
        function scrollToBottom() {
            var messageBody = document.getElementById("messageBody");
            messageBody.scrollTop = messageBody.scrollHeight;
        }

        window.onload = function () {
            scrollToBottom(); // Cuộn xuống dưới khi trang được tải
        };
    </script>

    <script>
        $(document).ready(function () {
            getData({
                'user_id': {{auth()->user()->id}},
                'orderBy': 'created_at',
                'order': 'DESC',
            })
        })
    </script>

    <script>
        $(document).ready(function () {
            getDataUser({
                'user_id': {{auth()-> user() -> id}},
                'orderBy': 'created_at',
                'order': 'DESC',
                'page': '0',
                'pagesize': '5'
            })
        })
    </script>

@endsection
