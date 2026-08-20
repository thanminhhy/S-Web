@extends('frontend.layouts.app')
@section('menu-left')
<div class="left-sidebar">
    <h2>Account</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="{{route('frontend.myAccount')}}">Account</a></h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="{{route('frontend.myProduct')}}">My product</a></h4>
            </div>
        </div>

    </div><!--/category-products-->


</div>
@endsection
@section('content')
<div class="blog-post-area">
    <h2 class="title text-center">Update user</h2>
    <div class="signup-form"><!--sign up form-->
        <h2>User Update</h2>
        <form action="{{route('frontend.updateAccount',$user->id)}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row mb-3 align-items-center">
                <input type="text" name="name" placeholder="Name" value="{{$user->name}}" />
                @error('name')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <input type="email" name="email" placeholder="Email Address" value="{{$user->email}}" />
                @error('email')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <input type="text" name="address" placeholder="Address" value="{{$user->address}}" />
                @error('address')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <input type="text" name="phone" placeholder="Phone" value="{{$user->phone}}" />
                @error('phone')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <select name="id_country" name="id_country" style="margin-bottom: 10px; height: 40px; width: 100%;">
                    <option value="">-- Select Country --</option>
                    @foreach($countries as $country)
                    <option value="{{$country->id}}" {{old('id_country',$user->id_country) == $country->id ?'selected': ''}}>{{$country->name}}</option>
                    @endforeach
                </select>
                @error('id_country')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-sm-10" style="margin-bottom: 10px">
                    <img
                        src="{{$user->avatar ? asset($user->avatar) : ''}}"
                        width='150px'
                        height='150px'
                        id="preview"
                        style="object-fit: cover; {{$user->avatar ? '' : 'display: none;'}}">
                </div>
                <input type="file" name="avatar" id="avatar">
                @error('avatar')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <button type=" submit" class="btn btn-default">Update</button>
        </form>
    </div>
</div>
<script>
    const imageInput = document.getElementById('avatar');
    const preview = document.getElementById('preview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        console.log(file);

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    })
</script>
@endsection