<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    //
    public function search(Request $request)
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
        $safeKeyword = addcslashes($keyword, '%_');

        $products = Product::where('name', 'LIKE', "%{$safeKeyword}%")
            ->select('id', 'name')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }
}
