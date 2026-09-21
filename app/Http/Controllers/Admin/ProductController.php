<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showListProduct()
    {
        $products = Product::paginate(6);
        return view('admin.product.listProduct', compact('products'));
    }
}
