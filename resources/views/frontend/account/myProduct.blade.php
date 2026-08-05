@extends('frontend.layouts.app')
@section('menu-left')
<div class="left-sidebar">
    <h2>Account</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="#">Áccount</a></h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="#">My product</a></h4>
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
                <td class="image">image</td>
                <td class="description">name</td>
                <td class="price">price</td>

                <td class="total">action</td>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="cart_product">
                    <a href=""><img src="images/cart/one.png" alt=""></a>
                </td>
                <td class="cart_description">
                    <h4><a href="">Colorblock Scuba</a></h4>

                </td>
                <td class="cart_price">
                    <p>$59</p>
                </td>

                <td class="cart_total">
                    <a>edit</a>
                    <a>delete</a>
                </td>
            </tr>

        </tbody>
    </table>
    <div class="mt-3" style="display:flex; justify-content: flex-end; margin-bottom: 10px;">
        <button class="btn btn-success">Add New Product</button>
    </div>
</div>
@endsection