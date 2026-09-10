<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;



class SearchController extends Controller
{
    //
    public function liveSearch(Request $request)
    {
        $safeKeyword = $this->getSafeKeyword($request);

        $products = Product::where('name', 'LIKE', "%{$safeKeyword}%")
            ->select('id', 'name')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

    public function search(Request $request)
    {
        $safeKeyword = $this->getSafeKeyword($request);

        $products = Product::where('name', 'LIKE', "%{$safeKeyword}%")
            ->get();

        $categories = Category::get();
        $brands = Brand::get();

        return view('frontend.product.search', compact('products', 'categories', 'brands'));
    }

    public function advancedSearch(Request $request)
    {
        $products = Product::query();

        return response()->json([
            'status' => 'success',
            'message' => 'filter successfully!'
        ]);
    }

    private function getSafeKeyword(Request $request): ?string
    {
        //1. Validate data
        $request->validate([
            'keyword' => 'nullable|string|max:100'
        ]);

        //2. Làm sạch 2 đầu của chuỗi
        $keyword = trim($request->keyword);

        //3. Check whether the input is empty or not
        if (empty($keyword)) {
            return response()->json(['html' => '']);
        }

        //4. Thêm dấu \ trước các ký tự % và _ để thoát ký tự đặc biệt của LIKE(% và _)
        return addcslashes($keyword, '%_');
    }
}
