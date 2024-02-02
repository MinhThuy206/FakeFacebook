@extends('layout.layout')
@section('title')
    Register
@endsection
@section('content')
    <div class="container">
        <form id="register" action="{{route('register')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name">
                <div class="mb-1" id="name-error"></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name ="email" id="email" >
                <div class="mb-1" id="email-error"></div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone">
                <div class="mb-1" id="phone-error"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
                <div class="mb-1" id="password-error"></div>
            </div>

            <div class="mb-1" id="error"></div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

@endsection

@section('js')
    <script src="{{asset('/js/register.js')}}"></script>
@endsection
