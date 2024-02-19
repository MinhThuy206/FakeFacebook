@extends('page.auth.layout')
@section('title')
    Register
@endsection
@section('content')
    <section class="vh-100 justify-content-center" style="background-color: #BC8F8F;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block ">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img1.webp" alt="login form" style="object-fit: contain; max-width: 100%; max-height: 100%;" >
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black" style="background-color: #808080">
                                    <form id="register">
{{--                                        <div class="d-flex align-items-center mb-3 pb-1">--}}
{{--                                            <i class="fa fa-facebook-square" style="font-size:100px;color: dodgerblue"></i>--}}
{{--                                        </div>--}}
                                        <h1 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register your account</h1>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example17">Name</label>
                                            <input type="text" id="name" class="form-control form-control-lg" name="name">
                                            <div class="mb-1" id="name-error"></div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example17">Email address</label>
                                            <input type="email" id="email" class="form-control form-control-lg" name="email">
                                            <div class="mb-1" id="email-error"></div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example17">Phone</label>
                                            <input type="text" id="phone" class="form-control form-control-lg" name="phone">
                                            <div class="mb-1" id="phone-error"></div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example27">Password</label>
                                            <input type="password" id="password" class="form-control form-control-lg" name="password">
                                            <div class="mb-1" id="password-error"></div>
                                        </div>

                                        <div class="mb-1" id="error"></div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit">Register</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    <script src="{{asset('/js/register.js')}}"></script>
@endsection
