@extends('layout.layout')
@section('title')
    Post Status
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-3">
                <h4>Add Friend</h4>
                <br>
                <div id="add-friend-list">

                </div>
            </div>

            <div class="col-md-6">
                <h4>Create New Post</h4>
                <div class="container text-center">
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

                    <br>
                    <!-- Hiển thị danh sách bài đăng -->
                    <div id="postList">
                    </div>
                </div>
            </div>

            <div class="col-md-3">
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

            getDataUser({
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
