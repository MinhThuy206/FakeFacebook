@extends('layout.layout')
@section('title', 'Profile')

@section('content')
    <style>
        .custom-border {
            height: 560px;
            position: relative;
            background-color: #FFFFFF;
        }

        .cover-border {
            background-color: rgba(0, 0, 0, 0.1);
            cursor: pointer;
            /*border-bottom-right-radius: 10px;*/
            /*border-bottom-left-radius: 10px;*/
        }

        .profile-picture {
            width: 168px;
            height: 168px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #cccc;
            position: absolute;
            top: 70%;
            transform: translateY(-50%);
            left: 15%; /* Đặt ảnh đại diện ở giữa theo chiều ngang */
            /* Chia tỉ lệ cho ảnh đại diện nằm một nửa trên cover và một nửa trên username-avt */
            margin-left: -84px; /* Độ lệch sang trái 1 nửa chiều rộng của ảnh đại diện */
        }

        .username {
            color: #333;
            overflow: hidden;
            position: absolute;
            left: 25%;
            top: 73%;
            font-weight: bold;
            font-size: 25px;
        }

        .user-friends {
            color: #333;
            overflow: hidden;
            position: absolute;
            left: 25%;
            top: 80%;
            font-size: 16px;
        }

        .username-avt {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            overflow: hidden;
            display: flex; /* Sử dụng flexbox */
            justify-content: space-between; /* Đặt các thành phần cách đều nhau */
            align-items: center; /* Canh chỉnh các thành phần theo chiều dọc */
        }

        .edit-profile button {
            /* Thay đổi CSS để sử dụng flexbox */
            padding: 7px 16px;
            transition: background-color 0.3s;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            font-weight: 500;
        }

        .edit-profile-button{
            margin-left: 70%;
            font-size: 16px;
        }

        .profile-options {
            display: flex;
            text-align: center;
            margin: auto;
        }

        .profile-option {
            padding: 7px 16px;
            transition: background-color 0.3s;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            font-weight: 500;
        }

        .profile-option:hover {
            background-color: #e0e0e0;
        }

        .profile-option.active {
            text-decoration: underline;
            color: #007bff; /* Màu xanh */
        }

        .profile-option span {
            border-bottom: 3px solid transparent; /* Tạo một đường gạch chân màu trong suốt */
            padding-bottom: 13px; /* Khoảng cách 5px giữa chữ và gạch chân */
            display: inline-block; /* Đảm bảo rằng các span không chiếm toàn bộ width */
        }

        .profile-option.active span {
            border-color: #007bff; /* Màu xanh cho đường gạch chân */
        }

        .cover-border img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Đảm bảo ảnh bìa nằm trọn trong khung chứa */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Đảm bảo ảnh đại diện nằm trọn trong khung chứa */
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

        .type-image{
            margin-bottom: -14px;
        }

    </style>

    <div class="container-fluid" style="margin-top: 56px;">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8 custom-border">
                <div class="row cover-border" id="cover" style="height: 70%;">
                    <img src="{{ asset($data['cover_url']) }}" alt="...">
                </div>

                <div class="row username-avt d-flex align-items-center justify-content-between" id="username-avt" style="height: 20%;">
                    <div class="profile-picture">
                        <img src="{{ asset($data['avatar_url']) }}" alt="...">
                    </div>
                    <div>
                        <div class="username">{{ $data['name'] }}</div>
                        <div class="user-friends">{{ $data['friends'] }} bạn bè</div>
                    </div>
                    @if(auth()->user()->id == $data['id'])
                        <div class="edit-profile">
                            <button class="edit-profile-button">Chỉnh sửa trang cá nhân</button>
                        </div>
                    @endif
                </div>

                <div class="row" style="height: 10%; margin: auto">
                    <div class="profile-options">
                        <a href="#" id="info" class="profile-option active">
                            <span class="info"> Bài viết</span>
                        </a>
                        <a href="#" id="about" class="profile-option">
                            <span class="user-about">Giới Thiệu</span>
                        </a>
                        <a href="#" id="user-friends" class="profile-option">
                            <span class="user-friend"> Bạn Bè</span>
                        </a>
                        <a href="#" id="images" class="profile-option">
                            <span class="user-image">Ảnh</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="height: 100vh;background-color:#E8E8E8">
                <div class="col-lg-8" style="background-color: #FFFFFF">
                    <!-- Content goes here -->
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Chỉnh sửa trang cá nhân</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="type-image">Ảnh đại diện</p>
                    <div class="container text-center">
                        <form id="postForm">
                            @csrf
                            <label for="content"></label>
                            <input id="content" name="content" class="form-control"
                                   style="width: 100%;height: 80px">
                            <div class="mt-3 d-flex">
                                <input type="file" multiple accept="image/*" id="image" onchange="xulyfile()" name="f1">
                                <button type="submit" class="btn btn-primary ml-3">Thêm</button>
                            </div>
                            <div id="imagePreview"></div>
                            <div class="mb-1" id="error"></div>
                        </form>
                    </div>

{{--                    <p class="type-image">Ảnh bìa</p>--}}
{{--                    <div class="container text-center">--}}
{{--                        <form id="postForm">--}}
{{--                            @csrf--}}
{{--                            <label for="content"></label>--}}
{{--                            <input id="content" name="content" class="form-control"--}}
{{--                                      style="max-width: 100%; height: 80px">--}}
{{--                            <br>--}}
{{--                            <div class="mt-3 d-flex">--}}
{{--                                <input type="file" multiple accept="image/*" id="image" onchange="xulyfile()" name="f1">--}}
{{--                                <button type="submit" class="btn btn-primary ml-3">Thêm</button>--}}
{{--                            </div>--}}
{{--                            <div id="imagePreview"></div>--}}
{{--                            <div class="mb-1" id="error"></div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('/js/profile.js')}}"></script>
@endsection


