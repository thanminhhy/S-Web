<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(6)->get();
        // dd(Auth::check());
        // dd(Auth::user());
        return view('frontend.home', compact('products'));
    }
}
