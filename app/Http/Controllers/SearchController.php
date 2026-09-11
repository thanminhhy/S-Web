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
        //1. Validate data
        $request->validate([
            'keyword' => 'nullable|string|max:100'
        ]);

        $safeKeyword = $this->getSafeKeyword($request->keyword);

        $products = Product::where('name', 'LIKE', "%{$safeKeyword}%")
            ->select('id', 'name')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

    // public function search(Request $request)
    // {
    //     //1. Validate data
    //     $request->validate([

    //         'keyword' => 'nullable|string|max:100'
    //     ]);

    //     $safeKeyword = $this->getSafeKeyword($request->keyword);

    //     $products = Product::where('name', 'LIKE', "%{$safeKeyword}%")
    //         ->paginate(3);

    //     $categories = Category::get();
    //     $brands = Brand::get();

    //     return view('frontend.product.search', compact('products', 'categories', 'brands'));
    // }

    public function search(Request $request)
    {
        // Validate input
        $request->validate([
            'keyword' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
            'price' => 'nullable|string|in:asc,desc',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'status' => 'nullable|string|in:sale,new'
        ]);
        $query = Product::query();
        $categories = Category::get();
        $brands = Brand::get();

        //filled check is input exist or not with has() method then check is it a "" or not
        if ($request->filled('name')) {
            $safeName = $this->getSafeKeyword($request->name);
            $query->where('name', 'LIKE', "%{$safeName}%");
        }

        if ($request->filled('keyword')) {
            $safeKeyword = $this->getSafeKeyword($request->keyword);
            $query->where('name', 'LIKE', "%{$safeKeyword}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('price')) {
            $sort = $request->price === 'asc' ? 'asc' : 'desc';
            $query->orderBy('price', $sort);
        }

        //Paginate and keep the lastest URL
        $products = $query->paginate(3)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'filter successfully!',
                'html' => view('frontend.product.layout.product-list', compact('products'))->render()
            ]);
        }
        return view('frontend.product.search', compact('products', 'categories', 'brands'));
    }

    private function getSafeKeyword(?string $keyword): ?string
    {
        //1. check null
        if (is_null($keyword)) {
            return null;
        }

        //2. Làm sạch 2 đầu của chuỗi
        $keyword = trim($keyword);

        //3. Check whether the input is empty or not
        if ($keyword === '') {
            return null;
        }

        //4. Thêm dấu \ trước các ký tự % và _ để thoát ký tự đặc biệt của LIKE(% và _)
        return addcslashes($keyword, '%_');
    }
}
