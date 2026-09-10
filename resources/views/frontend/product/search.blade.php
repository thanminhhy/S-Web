@extends('frontend.layouts.app')
@section('menu-left')
@include('frontend.layouts.menu-left')
@endsection

@section('content')
<div class="product-filter-wrapper">
    <form id="filter-form"
        class="filter-form"
        action="{{route('frontend.advancedSearch')}}"
        method="GET">
        <div class="filter-inputs">
            <!-- 1. Input name -->
            <div class="filter-group">
                <input
                    type="text"
                    name="name"
                    placeholder="Name"
                    value="{{request('name')}}"
                    class="filter-control">
            </div>
            <!-- 2. Select Price -->
            <div class="filter-group">
                <select name="price"
                    class="filter-control">
                    <option value="">Price</option>
                    <option value="asc" {{request('price') == 'asc' ? 'selected' : ''}}>Price: Low to High</option>
                    <option value="desc" {{request('price') == 'desc' ? 'selected' : ''}}>Price: High to Low</option>
                </select>
            </div>
            <!-- 3. Select Category -->
            <div class="filter-group">
                <select name="category_id"
                    class="filter-control">
                    <option value="">Category</option>
                    @if(isset($categories))
                    @foreach($categories as $category)
                    <option value="{{$category->id}}" {{request('category_id') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <!-- 4. Select Brand -->
            <div class="filter-group">
                <select name="brand_id"
                    class="filter-control">
                    <option value="">Brand</option>
                    @if(isset($brands))
                    @foreach($brands as $brand)
                    <option value="{{$brand->id}}" {{request('brand_id') == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                    @endforeach
                    @endif

                </select>
            </div>
            <!-- 4. Select Status -->
            <div class="filter-group">
                <select name="status"
                    class="filter-control">
                    <option value="">Status</option>
                    <option value="new" {{request('status') == 'new' ? 'selected' : ''}}>New</option>
                    <option value="sale" {{request('status') == 'sale' ? 'selected' : ''}}>Sale</option>
                </select>
            </div>
        </div>
        <!-- submit button -->
        <div class="filter-actions">
            <button type="submit" class="btn-search">Search</button>
        </div>
    </form>
</div>
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