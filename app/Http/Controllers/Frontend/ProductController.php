<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function showProductDetail(Product $product)
    {
        return view('frontend.product.detail', compact('product'));
    }
}
