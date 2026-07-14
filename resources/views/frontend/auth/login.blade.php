@extends('frontend.auth.layout.app')
@section('content')
<section id="form"><!--form-->
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-1">
                <div class="login-form"><!--login form-->
                    <h2>Login to your account</h2>
                    <form action="{{route('frontend.login')}}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <input type="email"
                                name="email"
                                placeholder="Email Address"
                                class="form-control @error('email') is-invalid @enderror" />
                            @error('email')
                            <span>
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <input type="password"
                                name="password"
                                placeholder="Password"
                                class="form-control @error('password') is-invalid @enderror" />
                            @error('password')
                            <span>
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <span>
                            <input type="checkbox" class="checkbox">
                            Keep me signed in
                        </span>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <button type="submit" class="btn btn-default">Login</button>
                    </form>
                    <a href="{{route('frontend.register')}}">Register?</a>
                </div><!--/login form-->
            </div>
        </div>
    </div>
</section><!--/form-->
@endsection