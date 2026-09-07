@extends('frontend.layouts.app')
@section('content')
<div id="cart_items">
    <div class="review-payment">
        <h2>Review & Payment</h2>
    </div>

    <div class="table-responsive cart_info">
        <table class="table table-condensed">
            <thead>
                <tr class="cart_menu">
                    <td class="image">Item</td>
                    <td class="description">Description </td>
                    <td class="price">Price</td>
                    <td class="quantity">Quantity</td>
                    <td class="total">Total</td>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td class="cart_product">
                        <a href=""><img src="{{asset('/upload/product/small/'.$item['images'][0])}}" alt=""></a>
                    </td>
                    <td class="cart_description">
                        <h4><a href="">{{$item['name']}}</a></h4>
                        <p>Product ID: {{$item['id']}}</p>
                    </td>
                    <td class="cart_price">
                        <p>{{$item['price']}} VND</p>
                    </td>
                    <td class="cart_quantity">
                        <div class="cart_quantity_button">
                            <input class="cart_quantity_input" type="text" name="quantity" value="{{$item['quantity']}}" autocomplete="off" size="2" disabled>
                        </div>
                    </td>
                    <td class="cart_total">
                        <p class="cart_total_price">{{$item['subTotal']}} VND</p>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td colspan="2">
                        <table class="table table-condensed total-result">
                            <tr>
                                <td>Cart Sub Total</td>
                                <td>{{$data['cartSubTotal']}} VND</td>
                            </tr>
                            <tr>
                                <td>Exo Tax</td>
                                <td>{{$data['ecoTax']}} VND</td>
                            </tr>
                            <tr class="shipping-cost">
                                <td>Shipping Cost</td>
                                <td>{{$data['shippingCost']}} VND</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><span>{{$data['grandTotal']}} VND</span></td>
                            </tr>
                        </table>
                        @php
                        $cart = session()->get('cart',[]);
                        $isCartEmpty = empty($cart) || count($cart) === 0;
                        @endphp
                        <button type="button"
                            class="btn btn-default order "
                            id="order-btn"
                            data-url="{{route('frontend.cart.checkout.process')}}"
                            {{$isCartEmpty ? 'disabled': ''}}>Order</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="payment-options">
        <span>
            <label><input type="checkbox"> Direct Bank Transfer</label>
        </span>
        <span>
            <label><input type="checkbox"> Check Payment</label>
        </span>
        <span>
            <label><input type="checkbox"> Paypal</label>
        </span>
    </div>

</div>

@endsection