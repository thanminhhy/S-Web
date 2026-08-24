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
    <script src="{{asset('frontend/js/cart.js')}}"></script>
    @stack('scripts')
</body>

</html>