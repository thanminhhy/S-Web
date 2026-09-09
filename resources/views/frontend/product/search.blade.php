@extends('frontend.layouts.app')
@section('menu-left')
@include('frontend.layouts.menu-left')
@endsection

@section('content')
<div class="features_items">
    <!--features_items-->
    <h2 class="title text-center">Features Items</h2>
    <div class="row">
        <div class="col-xs-12">
            @foreach($products as $product)
            <div class="col-sm-4">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{asset('upload/product/full/'.$product->images[0])}}" alt="" />
                            <h2>{{$product->price}} VND</h2>
                            <p>{{$product->name}}</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                        </div>
                        <disv class="product-overlay">
                            <div class="overlay-content">
                                <a href="{{route('frontend.product.detail',$product->id)}}">
                                    <h2>{{$product->price}} VND</h2>
                                    <p>{{$product->name}}</p>
                                </a>
                                <a class="btn btn-default add-to-cart" data-id="{{$product->id}}"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                            </div>
                        </disv>
                    </div>
                    <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                            <li>
                                <a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-plus-square"></i>Add to compare</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach

            <!--features_items-->
        </div>

    </div>
</div>
@endsection