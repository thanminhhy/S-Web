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

    public function showCart()
    {
        $cart = session()->get('cart', []);

        $cartSubTotal = 0;
        foreach ($cart as $id => $item) {

            $subTotal = $item['price'] * $item['quantity'];

            $cart[$id]['subTotal'] = $subTotal;
            $cartSubTotal += $subTotal;
        }

        return view('frontend.cart.index', compact('cart', 'cartSubTotal'));
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
            $cartSubTotal = 0;
            $itemSubTotal = $cart[$productId]['quantity'] * $cart[$productId]['price'];
            foreach ($cart as $id => $item) {
                $subTotal = $item['price'] * $item['quantity'];

                $cart[$id]['subTotal'] = $subTotal;
                $cartSubTotal += $subTotal;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Update cart successfully!',
                'cart_count' => count($cart),
                'itemSubTotal' => $itemSubTotal,
                'cartSubTotal' => $cartSubTotal
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
        ], 404);
    }
}
