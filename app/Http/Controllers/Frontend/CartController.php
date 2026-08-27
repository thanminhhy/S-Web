<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function addToCart(Request $request)
    {
        //1. Validate dữ liệu đầu vào
        $request->validate([
            'productId' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->productId;
        $productQty = (int)$request->quantity;
        $product = Product::find($productId);

        //2. Check product có tổn tại không
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại!', 404]);
        }

        //3. Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);
        //. Logic để cập nhật products trong cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $productQty;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $productQty,
                'images' => $product->images
            ];
        }

        session()->put('cart', $cart);

        //4. Tính tổng số lượng sản phẩm
        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm vào giỏ hàng thành công!',
            'cart_count' => count($cart),
            'totalQuantity' => $totalQuantity
        ]);
    }

    public function updateCartQuantity(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:products,id',
            'newQty' => 'required|integer|min:1'
        ]);

        $productId = $request->productId;
        $newQty = $request->newQty;

        //Get cart from session
        $cart = session()->get('cart', []);

        //check Cart exist or not
        if (isset($cart[$productId])) {
            //validate if $newQty is negative number or 0, the cart['quantity'] = 1
            $cart[$productId]['quantity'] = max(1, $newQty);

            session()->put('cart', $cart);
            $itemSubTotal = $cart[$productId]['quantity'] * $cart[$productId]['price'];
            $data = $this->calcCartGrandTotal($cart);

            return response()->json([
                'status' => 'success',
                'message' => 'Update cart successfully!',
                'cart_count' => count($cart),
                'itemSubTotal' => $itemSubTotal,
                'cartSubTotal' => $data['cartSubTotal'],
                'shippingCost' => $data['shippingCost'],
                'ecoTax' => $data['ecoTax'],
                'grandTotal' => $data['grandTotal']
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
        ], 404);
    }

    public function showCart()
    {
        // session()->forget('cart');
        $cart = session()->get('cart', []);
        $data = $this->calcCartGrandTotal($cart);

        return view('frontend.cart.index', compact('cart', 'data'));
    }

    public function showCheckoutForm()
    {
        $$cart = session()->get('cart', []);
        $data = $this->calcCartGrandTotal($cart);

        return view('frontend.cart.checkout', compact('cart', 'data'));
    }

    private function calcCartGrandTotal($cart)
    {
        $cartSubTotal = 0;
        $totalEcoTax = 0;

        foreach ($cart as $id => $item) {
            $subTotal = $item['price'] * $item['quantity'];
            $subEcoTax = (int)$item['quantity'] * 2000;
            $cart[$id]['subTotal'] = $subTotal;

            $totalEcoTax += $subEcoTax;
            $cartSubTotal += $subTotal;
        }

        session()->put('cart', $cart);

        $shippingCost = ($cartSubTotal >= 500000 || $cartSubTotal == 0) ? 0 : 30000;
        $grandTotal = $totalEcoTax + $cartSubTotal + $shippingCost;
        return
            [
                'cartSubTotal' => $cartSubTotal,
                'shippingCost' => $shippingCost,
                'ecoTax' => $totalEcoTax,
                'grandTotal' => $grandTotal,
            ];
    }
}
