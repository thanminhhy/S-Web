<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home | E-Shopper</title>
    <link href="{{asset("/frontend/css/bootstrap.min.css")}}" rel="stylesheet" />
    <link href="{{asset("/frontend/css/font-awesome.min.css")}}" rel="stylesheet" />
    <link href="{{asset("/frontend/css/prettyPhoto.css")}}" rel="stylesheet" />
    <link href="{{asset("/frontend/css/price-range.css")}}" rel="stylesheet" />
    <link href="{{asset("/frontend/css/animate.css")}}" rel="stylesheet" />
    <link href="{{asset("/frontend/css/main.css")}}" rel="stylesheet" />
    <link href="{{asset("/frontend/css/responsive.css")}}" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="{{asset("/frontend/images/ico/favicon.ico")}}" />
    <link
        rel="apple-touch-icon-precomposed"
        sizes="144x144"
        href="{{asset("/frontend/images/ico/apple-touch-icon-144-precomposed.png")}}" />
    <link
        rel="apple-touch-icon-precomposed"
        sizes="114x114"
        href="{{asset("/frontend/images/ico/apple-touch-icon-114-precomposed.png")}}" />
    <link
        rel="apple-touch-icon-precomposed"
        sizes="72x72"
        href="{{asset("/frontend/images/ico/apple-touch-icon-72-precomposed.png")}}" />
    <link
        rel="apple-touch-icon-precomposed"
        href="{{asset("/frontend/images/ico/apple-touch-icon-57-precomposed.png")}}" />
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">

        @include('frontend.layouts.header')

        @include('frontend.layouts.slide')

        <section>
            <div class="container">
                <div class="row">
                    <div class='col-sm-3'>
                        @yield('menu-left')
                    </div>
                    <div class="col-sm-9 padding-rignt">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>


        @include('frontend.layouts.footer')
    </div>

    <script src="{{asset('frontend/js/jquery.js')}}"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="{{asset('frontend/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('frontend/js/jquery.scrollUp.min.js')}}"></script>
    <script src="{{asset('frontend/js/price-range.js')}}"></script>
    <script src="{{asset('frontend/js/jquery.prettyPhoto.js')}}"></script>
    <script src="{{asset('frontend/js/main.js')}}"></script>
    <script>
        $(document).ready(function() {
            //vote
            $('.ratings_stars').hover(
                // Handles the mouseover
                function() {
                    $(this).prevAll().andSelf().addClass('ratings_hover');
                    // $(this).nextAll().removeClass('ratings_vote'); 
                },
                function() {
                    $(this).prevAll().andSelf().removeClass('ratings_hover');
                    // set_votes($(this).parent());
                }
            );

            $('.ratings_stars').click(function() {
                //check login status
                var isLoggedIn = "{{Auth::check()}}";

                if (isLoggedIn) {
                    var rate = $(this).find("input").val();
                    var blogId = $(this).closest('.rate').data('id');
                    // console.log(userId);
                    if ($(this).hasClass('ratings_over')) {
                        $('.ratings_stars').removeClass('ratings_over');
                        $(this).prevAll().andSelf().addClass('ratings_over');
                    } else {
                        $(this).prevAll().andSelf().addClass('ratings_over');
                    }

                    $.ajax({
                        type: 'POST',
                        url: '{{route("blog.rate")}}',
                        data: {
                            rate: rate,
                            blog_id: blogId
                        },
                        success: function(data) {
                            var avg = Math.round(Number(data.rating_avg))
                            var startsHtml = '';
                            for (var i = 1; i <= 5; i++) {
                                var activeClass = i <= data.rating_avg ? 'color' : '';
                                startsHtml += `<i class='fa fa-star ${activeClass} '></i>`
                            }
                            alert(data.message);
                            $('.rate-np').text(data.rating_avg);
                            $('.rate-star').html(startsHtml);
                            $('.rate-count').text(data.rating_count == 1 ? `${data.rating_count} vote` : `${data.rating_count} votes`);
                            // alert(`${data.message}. Bài viết có số lượng đánh giá là ${data.rating_count} với số điểm đánh giá tổng là ${data.rating_avg}`);
                        }
                    });
                } else {
                    alert('Vui lòng login để rate');
                    window.location.href = "{{route('frontend.login')}}"
                }
            });

            //-----------------Comment Area----------------
            //======  Parent comment
            $(document).on('submit', '.ajax-comment-form', function(e) {
                e.preventDefault();

                //check login status
                var isLoggedIn = "{{Auth::check()}}";
                if (isLoggedIn) {
                    var cmt = $('#cmt-content').val();

                    //<div class="replay-box" data-id="..."> ----- .data('id') 
                    //<div class="replay-box" data-blog-id="..."> ----- .data('blog-id')
                    var blogId = $(this).closest('.replay-box').data('id');
                    let form = $(this);
                    let submitBtn = form.find('button[type="submit"]');



                    $.ajax({
                        type: 'POST',
                        url: '{{route("blog.comment")}}',
                        data: {
                            comment: cmt,
                            blog_id: blogId
                        },
                        success: function(data) {
                            alert(data.message);
                            var comment = data.data;
                            var userName = comment.user.name;
                            var userAvatar = comment.user.avatar;

                            var newCommentHtml = `<li class="media">
                                                    <a class="pull-left" href="#">
                                                        <img class="media-object" src="${userAvatar}" alt="">
                                                    </a>
                                                    <div class="media-body">
                                                        <ul class="sinlge-post-meta">
                                                            <li><i class="fa fa-user"></i>${userName}</li>
                                                            <li><i class="fa fa-clock-o"></i> ${data.formatedTime}</li>
                                                            <li><i class="fa fa-calendar"></i> ${data.formatedDate}</li>
                                                        </ul>
                                                        <p>${comment.comment}</p>
                                                        <button class="btn btn-primary btn-toggle-reply" type="button" data-id="${comment.id}"'><i class="fa fa-reply"></i>Replay</button>
                                                    </div>

                                                    <!-- khung form bị ẩn-->
                                                    <div class="reply-form-wrapper mt-3 hide" id="reply-form-box-${comment.id}">
                                                        <form class="ajax-reply-form">
                                                            @csrf
                                                            <input type="hidden" name="blog_id" value="${blogId}">
                                                            <input type="hidden" name="parent_id" value="${comment.id}">

                                                            <div class="text-area">
                                                                <div class="blank-arrow">
                                                                    <label>Your Name</label>
                                                                </div>
                                                                <span>*</span>
                                                                <textarea name="comment" id='child-comment-content-${comment.id}' rows="2"></textarea>
                                                                <button class="btn btn-primary" type="submit" disabled>Comment</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <!-- khung chứa các comment con mới-->
                                                    <div id="replies-for-${comment.id}"></div>
                                                </li>`;

                            $('.media-list').prepend(newCommentHtml);

                            //xóa nội dung trong ô cmt
                            $('#cmt-content').val('');

                            submitBtn.prop('disabled', true);
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) {
                                alert('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!');
                                window.location.href = "{{route('frontend.login')}}";
                            } else {
                                alert('Có lỗi xảy ra, vui lòng thử lại sau.');
                            }
                        }
                    })


                } else {
                    alert('Vui lòng login để comment');
                    window.location.href = "{{route('frontend.login')}}"
                }
            })

            //Disable submit parent-comment button when comment box is empty
            $(document).on('input', 'textarea[id^="cmt-content"]', function() {
                let content = $(this).val().trim();
                let submitBtn = $(this).closest('form').find('button[type="submit"]');

                submitBtn.prop('disabled', content.length === 0)
            })

            $(document).on('keydown', 'textarea[id^="cmt-content"]', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    let content = $(this).val().trim();
                    let form = $(this).closest('form');
                    if (content.length > 0) {
                        form.submit();
                    }
                }
            })

            //Toggle replies on parent comment
            $(document).on('click', '.btn-toggle-replies-list', function(e) {
                e.preventDefault();
                let parentId = $(this).data('id');
                let repliesBox = $('#replies-for-' + parentId);

                repliesBox.slideToggle(200);
            })


            //====== Child comment
            $(document).on('click', '.btn-toggle-reply', function(e) {
                e.preventDefault();
                let parentId = $(this).data('id');

                $('#reply-form-box-' + parentId).toggleClass('hide');
            })


            //Disable submit child-comment button when comment box is empty
            $(document).on('input', 'textarea[id^="child-comment-content-"]', function() {
                let content = $(this).val().trim();
                let submitBtn = $(this).closest('form').find('button[type="submit"]');

                submitBtn.prop('disabled', content.length === 0);
            })

            //Handle enter key when user push enter, UI should submit form
            $(document).on('keydown', 'textarea[id^="child-comment-content-"]', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    let content = $(this).val().trim();
                    let form = $(this).closest('form');

                    if (content.length > 0) {
                        form.submit();
                    }
                }
            })

            $(document).on('submit', '.ajax-reply-form', function(e) {
                e.preventDefault();
                var isLoggedIn = "{{Auth::check()}}";

                if (isLoggedIn) {
                    let form = $(this);
                    let submitBtn = form.find('button[type="submit"]');

                    //Vô hiệu hóa nút tạm thời tránh spam nút
                    submitBtn.prop('disabled', true).text('Sending...');
                    $.ajax({
                        type: 'POST',
                        url: '{{route("blog.comment")}}',
                        data: form.serialize(),
                        success: function(data) {
                            alert(data.message);
                            var comment = data.data;
                            var userName = comment.user.name;
                            var userAvatar = comment.user.avatar;
                            console.log(comment, userName, userAvatar);

                            var newCommentHtml = `<ul class="media second-media">
                                                <li>
                                                    <a class="pull-left" href="#">
                                                        <img class="media-object" src="${userAvatar}" alt="">
                                                    </a>
                                                    <div class="media-body">
                                                        <ul class="sinlge-post-meta">
                                                            <li><i class="fa fa-user"></i>${userName}</li>
                                                            <li><i class="fa fa-clock-o"></i> ${data.formatedTime}</li>
                                                            <li><i class="fa fa-calendar"></i> ${data.formatedDate}</li>
                                                        </ul>
                                                        <p>${comment.comment}</p>
                                                    </div>
                                                </li>
                                                </ul>`;

                            //1. append new child comment to list
                            $(`#replies-for-${comment.parent_id}`).append(newCommentHtml);

                            //Xử lý nút ẩn hiện cmt con
                            let toggleBtn = $(`.btn-toggle-replies-list[data-id="${comment.parent_id}"]`);
                            let totalReplies = $(`#replies-for-${comment.parent_id} .second-media`).length

                            //toggleBtn.length mà lớn hơn 0 có nghĩa là đã còn cmt con còn sai thì chưa có
                            if (toggleBtn.length > 0) {
                                toggleBtn.find(`.replies-count-text-${comment.parent_id}`).text(`Xem ${totalReplies} câu trả lời`);
                            } else {
                                newToggleBtnHtml = `<a href="javascript:void(0)" class="btn-toggle-replies-list ml-2" data-id="${comment.parent_id}" style="text-decoration: none; font-size: 13px; color: #fe980f; margin-left: 10px;">
                                                        <i class="fa fa-comments"></i>
                                                        <span class="replies-count-text-${comment.parent_id}">
                                                            Xem ${totalReplies} câu trả lời
                                                        </span>
                                                    </a>`;
                                $(`.btn-toggle-reply[data-id="${comment.parent_id}"]`).after(newToggleBtnHtml);
                            }
                            //xóa nội dung trong ô cmt và vô hiệu hóa nút cmt
                            $(`#child-comment-content-${comment.parent_id}`).val('');
                            submitBtn.prop('disabled', true).text('Comment');

                            //Ẩn ô cmt và hiển thị danh sách cmt con
                            $(`#reply-form-box-${comment.parent_id}`).toggleClass('hide');
                            $(`#replies-for-${comment.parent_id}`).slideDown(200);
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) {
                                alert('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!');
                                window.location.href = "{{route('frontend.login')}}";
                            } else {
                                alert('Có lỗi xảy ra, vui lòng thử lại sau.');
                            }
                        }
                    })
                } else {
                    alert('Vui lòng login để comment');
                    window.location.href = "{{route('frontend.login')}}"
                }

            })
        });
    </script>
</body>

</html>