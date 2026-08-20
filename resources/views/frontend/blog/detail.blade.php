@extends('frontend.layouts.app')
@section('content')
<div class="blog-post-area">
    <h2 class="title text-center">Latest From our Blog</h2>
    <div class="single-blog-post">
        <h3>{{$blog->title}}</h3>
        <div class="post-meta">
            <ul>
                <li><i class="fa fa-user"></i> Mac Doe</li>
                <li><i class="fa fa-clock-o"></i> {{$blog->created_at->format('h:i A')}}</li>
                <li><i class="fa fa-calendar"></i> {{$blog->created_at->format('d/m/Y')}}</li>
            </ul>
            <div class="rate" data-id="{{$blog->id}}">
                <div class="vote">
                    @for($i = 1; $i<=5; $i++)
                        <div class="star_{{$i}} ratings_stars {{$i <= $userRating ? 'ratings_vote ratings_over' : ''}}"><input value="{{$i}}" type="hidden"></div>
                @endfor
                <!-- <div class="star_1 ratings_stars"><input value="1" type="hidden"></div> -->
                <span class="rate-np">{{$blog->rating_avg}}</span>
            </div>
        </div>
    </div>

    <div class="content-post">
        {!! clean($blog->content) !!}
    </div>

</div>
</div><!--/blog-post-area-->

<!-- --------------------------Rating Area---------------------------- -->

<div class="rating-area">
    <ul class="ratings">
        <li class="rate-this">Rate this item:</li>
        <li class="rate-star">
            @for($i=1; $i<=5; $i++)
                <i class="fa fa-star {{$i <= $blog->rating_avg ? 'color' : ''}}"></i>
                @endfor
        </li>
        <li class="color rate-count">({{$blog->rating_count == 1? '1 vote' : "{$blog->rating_count} votes"}})</li>
        <!-- <li class="color">({{$blog->rating_count == 1? '1 vote' : $blog->rating_count. ' votes'}})</li> -->
    </ul>
    <ul class="tag">
        <li>TAG:</li>
        <li><a class="color" href="">Pink <span>/</span></a></li>
        <li><a class="color" href="">T-Shirt <span>/</span></a></li>
        <li><a class="color" href="">Girls</a></li>
    </ul>
</div><!--/rating-area-->

<div class="socials-share">
    <a href=""><img src="images/blog/socials.png" alt=""></a>
</div>

<!-- ----------------------------Respone Area--------------------------- -->
<div class="response-area">
    <h2>{{$comments->count()}} RESPONSES</h2>
    <div class="replay-box" data-id="{{$blog->id}}">
        <div class="row">
            <div class="col-sm-12">
                <h2>Leave a replay</h2>
                <form class='ajax-comment-form'>
                    @csrf
                    <div class="text-area">
                        <div class="blank-arrow">
                            <label>Your Name</label>
                        </div>
                        <span>*</span>
                        <textarea name="message" id='cmt-content' rows="6"></textarea>
                        <button class="btn btn-primary" id="comment" type="submit" disabled>Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!--/Repaly Box-->
    <ul class="media-list">
        @foreach($comments as $parentComment)
        <li class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="{{$parentComment->user->avatar}}" alt="">
            </a>
            <div class="media-body">
                <ul class="sinlge-post-meta">
                    <li><i class="fa fa-user"></i>{{$parentComment->user->name}}</li>
                    <li><i class="fa fa-clock-o"></i>{{$parentComment->created_at->format('h:i A')}}</li>
                    <li><i class="fa fa-calendar"></i> {{$parentComment->created_at->format('d/m/Y')}}</li>
                </ul>
                <p>{{$parentComment->comment }}</p>
                <button class="btn btn-primary btn-toggle-reply" data-id="{{$parentComment->id}}"><i class="fa fa-reply"></i>Replay</button>
                @if($parentComment->replies->count() > 0)
                <a href="javascript:void(0)" class="btn-toggle-replies-list ml-2" data-id="{{ $parentComment->id }}" style="text-decoration: none; font-size: 13px; color: #fe980f; margin-left: 10px;">
                    <i class="fa fa-comments"></i>
                    <span class="replies-count-text-{{ $parentComment->id }}">
                        Xem {{ $parentComment->replies->count() }} câu trả lời
                    </span>
                </a>
                @endif
            </div>

            <div class="reply-form-wrapper mt-3 hide" id="reply-form-box-{{$parentComment->id}}">
                <form class="ajax-reply-form">
                    @csrf
                    <input type="hidden" name="blog_id" value="{{$blog->id}}">
                    <input type="hidden" name="parent_id" value="{{$parentComment->id}}">

                    <div class="text-area">
                        <div class="blank-arrow">
                            <label>Your Name</label>
                        </div>
                        <span>*</span>
                        <textarea name="comment" id='child-comment-content-{{$parentComment->id}}' rows="2"></textarea>
                        <button class="btn btn-primary" type="submit" disabled>Comment</button>
                    </div>
                </form>
            </div>

            <div id="replies-for-{{$parentComment->id}}" class="replies-container" style="display: none; margin-left: 50px;">
                @foreach($parentComment->replies as $reply)
                <ul class="media second-media">
                    <li>
                        <a class="pull-left" href="#">
                            <img class="media-object" src="{{$reply->user->avatar}}" alt="">
                        </a>
                        <div class="media-body">
                            <ul class="sinlge-post-meta">
                                <li><i class="fa fa-user"></i>{{$reply->user->name}}</li>
                                <li><i class="fa fa-clock-o"></i> {{$reply->created_at->format('h:i:A')}}</li>
                                <li><i class="fa fa-calendar"></i> {{$reply->created_at->format('d/m/Y')}}</li>
                            </ul>
                            <p>{{$reply->comment}}</p>
                            <!-- <a class="btn btn-primary" href=""><i class="fa fa-reply"></i>Replay</a> -->
                        </div>
                    </li>
                </ul>
                @endforeach
            </div>
        </li>
        @endforeach

    </ul>
    <div class="pagination-area">
        {{$comments->onEachSide(0)->links('pagination::bootstrap-4')}}
    </div>
</div><!--/Response-area-->
@endsection

@push('scripts')
<!-- Truyền biến Laravel sang JavaScript biến toàn cục -->
<script>
    window.isLoggedIn = "{{Auth::check()}}";
    window.routes = {
        blogRate: "{{route('blog.rate')}}",
        blogComment: "{{ route('blog.comment') }}",
        login: "{{ route('frontend.login') }}"
    };
</script>

<!-- Nhúng file JS xử lý chuyên biệt -->
<script src="{{ asset('frontend/js/blog-detail.js') }}"></script>

@endpush