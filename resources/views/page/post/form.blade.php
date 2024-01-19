@extends('layout.layout')
@section('title')
    Post Status
@endsection
@section('content')
    <h1>Create New Post</h1>
    <form id="postForm">
        @csrf
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="4" cols="50"></textarea>
        <br>

        <div class="mb-1" id="error"></div>
        <button type="submit">Post</button>
    </form>

    <h1>Posts</h1>
    <!-- Hiển thị danh sách bài đăng -->
    <div id="postList">
    </div>

    <!-- Hiển thị bài đăng mới -->
    <div id="newPostContainer"></div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {
            getData({
                'user_id': {{auth()-> user() -> id}},
                'orderBy': 'created_at',
                'order': 'DESC',
                'page': '0',
                'pagesize': '5'
            })
        })
    </script>
@endsection
