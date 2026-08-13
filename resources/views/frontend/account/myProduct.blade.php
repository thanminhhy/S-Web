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
<div class="table-responsive cart_info">
    <table class="table table-condensed">
        <thead>
            <tr class="cart_menu">
                <td>ID</td>
                <td class="description">name</td>
                <td class="image">image</td>
                <td class="price">price</td>

                <td class="total">action</td>

            </tr>
        </thead>
        <tbody>
            @if($products->isEmpty())
            <tr>
                <td colspan="5" class="text-center">
                    <div class="alert alert-info mb-0">
                        Hiện tại không có sản phẩm nào!
                    </div>
                </td>
            </tr>
            @else
            @foreach($products as $product)
            <tr>
                <td>
                    {{$product->id}}
                </td>
                <td class="cart_description">
                    <h4><a href="">{{$product->name}}</a></h4>

                </td>
                <td class="cart_product">
                    @foreach(json_decode($product->images) as $image)
                    <a href=""><img src="{{asset('upload/product/small/'.$image)}}" alt=""></a>
                    @endforeach
                </td>
                <td class="cart_price">
                    <p>{{$product->price}} VND</p>
                </td>

                <td class="cart_total">
                    <a>edit</a>
                    <a>delete</a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <div class="mt-3" style="display:flex; justify-content: flex-end; margin-bottom: 10px;">
        <a
            type="button"
            class="btn btn-success"
            href="{{route('frontend.showCreateProductForm')}}">
            Add New Product
        </a>
    </div>
</div>
@endsection