@extends('frontend.layouts.app')
@section('menu-left')
@include('frontend.layouts.menu-left')
@endsection
@section('content')
<div class="blog-post-area">
    <h2 class="title text-center">Latest From our Blog</h2>
    @foreach($blogs as $blog)
    <div class="single-blog-post">
        <h3>{{$blog->title}}</h3>
        <div class="post-meta">
            <ul>
                <li><i class="fa fa-user"></i> Mac Doe</li>
                <li><i class="fa fa-clock-o"></i> {{$blog->created_at->format('h:i A')}}</li>
                <li><i class="fa fa-calendar"></i> {{$blog->created_at->format('d/m/Y')}}</li>
            </ul>
            <div class="rate">
                <div class="vote">
                    <div class="star_1 ratings_stars"><input value="1" type="hidden"></div>
                    <div class="star_2 ratings_stars"><input value="2" type="hidden"></div>
                    <div class="star_3 ratings_stars"><input value="3" type="hidden"></div>
                    <div class="star_4 ratings_stars"><input value="4" type="hidden"></div>
                    <div class="star_5 ratings_stars"><input value="5" type="hidden"></div>
                    <span class="rate-np">{{$blog->rating_avg}}</span>
                </div>
            </div>
        </div>
        <a href="{{route('frontend.blog.detail',$blog->id)}}">
            <img src="/{{$blog->image}}" style="width:20%" alt="">
        </a>
        <p>{{$blog->description}}</p>
        <a class="btn btn-primary" href="{{route('frontend.blog.detail',$blog->id)}}">Read More</a>
    </div>
    @endforeach
    <div class="pagination-area">
        {{$blogs->onEachSide(0)->links()}}
    </div>
</div>


@endsection