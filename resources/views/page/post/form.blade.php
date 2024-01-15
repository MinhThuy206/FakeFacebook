@extends('layout.layout')
@section('title')
    Post Status
@endsection
@section('content')
    <div class="container">
        <form id="post" action="{{route('post')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <input type="text" class="form-control" name="content" id="content">
                <div class="mb-1" id="content-error"></div>
            </div>

            <div class="mb-1" id="error"></div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

@endsection
