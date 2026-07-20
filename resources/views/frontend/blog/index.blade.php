@extends('frontend.layouts.app')
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
            <span>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half-o"></i>
            </span>
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