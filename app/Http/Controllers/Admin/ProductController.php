<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showListProduct()
    {
        $products = Product::paginate(6);
        return view('admin.product.listProduct', compact('products'));
    }

    public function showEditProduct(string $productId)
    {
        $product = Product::findOrFail($productId);
        $categories = Category::get();
        $brands = Brand::get();

        return view('admin.product.editProduct', compact('product', 'categories', 'brands'));
    }
}
