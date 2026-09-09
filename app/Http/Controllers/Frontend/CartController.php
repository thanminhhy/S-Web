<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;

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
        $cart = session()->get('cart', []);
        $data = $this->calcCartGrandTotal($cart);

        return view('frontend.cart.checkout', compact('cart', 'data'));
    }

    public function processCheckout()
    {
        $cart = session()->get('cart', []);

        //1. Check cart is empty or not
        if (empty($cart)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Giỏ hàng của bạn đang rỗng!'
            ], 400);
        }

        $dataOrder = $this->calcCartGrandTotal($cart);
        $user = Auth::user();

        //2. Save Order to database by using try catch block
        try {
            //Create Order
            $order = Order::Create([
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'total_price' => $dataOrder['grandTotal']
            ]);

            //Create list item in order
            foreach ($cart as $item) {
                OrderItem::Create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage()
            ], 500);
        }

        //3. Send mail by using try catch block
        $mailSent = true;
        try {
            Mail::to($order->email)->send(new MailNotify($order));
        } catch (Exception $e) {
            $mailSent = false;

            //Save errors to log. Meanwhile, Devs will handle there
            Log::error('Lỗi gửi mail đơn hàng #' . $order->id . ': ' . $e->getMessage());
        }

        //4. Delete cart in session
        // session()->forget('cart');

        if ($mailSent) {
            $message = 'Đặt hàng thành công! Hệ thống đang xử lý gửi email xác nhận đơn hàng đến bạn.';
            session()->forget('cart');
        } else {
            $message = 'Đặt hàng thành công! Tuy nhiên hệ thống không thể gửi email xác nhận lúc này.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'redirect_url' => route('frontend.index')
        ]);
    }

    public function previewMail()
    {
        $order = Order::with(['items', 'user'])->find(12);

        return view('frontend.emails.index', compact('order'));
    }


    //Use & in parameter to pass by reference. 
    //Any changes inside this function will directly update the origional $cart outside
    private function calcCartGrandTotal(&$cart)
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
