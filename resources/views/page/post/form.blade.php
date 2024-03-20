@extends('layout.layout')
@section('title')
    Post Status
@endsection
@section('content')
    <style>
        .main{
            border: solid 1px #ccc;
            background: #fff;
            border-radius: 10px;
            margin-top: 10px;
        }

        .card.mb-6.post {
            margin-top: 10px;
        }

        .card-content{
            background-color: #fff;
            border-bottom: #fff ;
        }

        .button-select-option{
            background-color: #fff;
            padding: 0px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: #fff;
        }

        .button-select-option:hover{
            background-color: #f9f9f9;
        }

        .button-select-option::after{
            color: #000;
        }

        .post {
            border: 1px solid #ddd; /* Add border for post */
            border-radius: 8px; /* Rounded corners */
            margin-bottom: 20px; /* Add space between posts */
        }

        .post-header {
            display: flex; /* Use flexbox for header */
            padding: 12px;
            border-bottom: 1px solid #ddd; /* Add border bottom for header */
        }

        .post-avatar {
            margin-right: 12px;
        }

        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%; /* Make avatar round */
        }

        .post-header-info{

        }

        .user-name {
            font-weight: bold;
        }

        .post-timestamp {
            color: #666;
        }

        .button-select-option {
            margin-left: auto; /* Đẩy nút option sang phải cùng của container cha */
        }

        .post-header-info{
            padding-right: 23rem;
        }

        .card-text{
            padding: 10px;
        }


        .card-body{
            padding: 0px;
        }

        .post-main{
            height: 1000px;
            overflow-y: scroll;
        }

        .post-main::-webkit-scrollbar {
            display: none;
        }

        .post-main {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }


    </style>
    <div class="container-fluid" style="margin-top: 56px; display: flex; flex-direction: column;">
        <div class="row content" style="flex: 1; background-color: #f9f9f9">
            <div class="col-md-4" id="session1" style="height: 100vh;">
                <h4>Add Friend</h4>
            </div>

            <div class="col-md-5 post-main" id="session2">
                <div class="container main">
                    <h4>Create New Post</h4>
                    <form id="postForm">
                        @csrf
                        <label for="content"></label>
                        <textarea id="content" name="content" class="form-control"
                                  style="width: 100%; max-width: 80rem;"></textarea>
                        <br>
                        <div class="mt-3 d-flex">
                            <input type="file" multiple accept="image/*" id="image" onchange="xulyfile()" name="f1">
                            <button type="submit" class="btn btn-primary ml-3">Post</button>
                        </div>
                        <div id="imagePreview"></div>
                        <div class="mb-1" id="error"></div>
                    </form>
                </div>
{{--                <div class="divider" style="background-color: #cbd5e0; height: 5px; padding: 0 12px"></div>--}}

                <div class="text-center">
                    <div id="postList">
                    </div>
                </div>
            </div>

            <div class="col-md-3" id="session3" style="height: 100vh;">
                <h4>List Friend</h4>
                <br>
                <div id="list-friend">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{asset('/js/post.js')}}"></script>
    <script src="{{asset('/js/friend.js')}}"></script>
    <script>
        $(document).ready(function () {
            getData({
                'user_id': {{auth()-> user() -> id}},
                'orderBy': 'created_at',
                'order': 'DESC',
                'page': '0',
                'pagesize': '5'
            })

            getFriend({
                'user_id': {{auth()-> user() -> id}},
                'orderBy': 'created_at',
                'order': 'DESC',
                'page': '0',
                'pagesize': '5'
            })
        })
    </script>

@endsection
