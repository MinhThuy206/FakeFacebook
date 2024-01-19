@extends('layout.layout')
@section('title')
    Login
@endsection
@section('content')
    <div class="container">
        <form id="login" action="{{route('login')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="text" class="form-control" name="email">
                <div class="mb-1" id="email-error"></div>
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
                <div class="mb-1" id="password-error"></div>
            </div>


            <div class="mb-1" id="error"></div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

@endsection
