@extends('frontend.auth.layout.app')
@section('content')
<section id='form'>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-1">
                <div class="signup-form"><!--sign up form-->
                    <h2>New User Signup!</h2>
                    <form action="{{route('frontend.register')}}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <input type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Name" />
                            @error('name')
                            <span>
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <input type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email Address" />
                            @error('email')
                            <span>
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Password" />
                            @error('password')
                            <span>
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <input type="password"
                                id="password-confirm"
                                name="password_confirmation"
                                placeholder=" Confirm Password" />
                        </div>
                        <div class="row mb-3">
                            <input type="hidden"
                                name="level"
                                value=0>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-default">Signup</button>
                            </div>
                        </div>
                    </form>
                </div><!--/sign up form-->
            </div>
        </div>
    </div>
</section><!--/form-->
@endsection