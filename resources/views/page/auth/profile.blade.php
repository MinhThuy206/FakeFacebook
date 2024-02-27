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
                    @if(auth()->user()->username == $data['username'])
                        <div class="edit-profile">
                            <button class="edit-profile-button">Chỉnh sửa trang cá nhân</button>

                            <div id="edit-menu" style="display: none;">
                                <a href="#" class="edit-option">Chỉnh sửa ảnh bìa</a>
                                <a href="#" class="edit-option">Chỉnh sửa ảnh đại diện</a>
                            </div>
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
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var profileOptions = document.querySelectorAll('.profile-option');
            profileOptions.forEach(function (option) {
                option.addEventListener("click", function (event) {
                    event.preventDefault();
                    profileOptions.forEach(function (opt) {
                        opt.classList.remove('active');
                    });
                    option.classList.add('active');
                });
            });

            var editProfileButton = document.getElementById('edit-profile-button');
            var editMenu = document.getElementById('edit-menu');
            editProfileButton.addEventListener('click', function () {
                if (editMenu.style.display === 'none') {
                    editMenu.style.display = 'block';
                } else {
                    editMenu.style.display = 'none';
                }
            });
        });
    </script>
    <script src="{{asset('/js/profile.js')}}"></script>
@endsection


