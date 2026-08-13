<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest\ProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Intervention\Image\Laravel\Facades\Image;

class MyProudctController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $products = Product::where('user_id', $userId)->Paginate(6);
        // dd($products->toArray());
        return view('frontend.account.myProduct', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontend.account.createProduct', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('images')) {
            $data['images'] = [];
            foreach ($request->file('images') as $file) {
                $image = Image::read($file);
                $fileName = time() . '_' . $file->getClientOriginalName();

                $pathSmall = public_path('upload/product/small/' . $fileName);
                $pathMedium = public_path('upload/product/medium/' . $fileName);
                $pathFull = public_path('upload/product/full/' . $fileName);

                $image->resize(50, 70)->save($pathSmall);
                $image->resize(120, 120)->save($pathMedium);
                $image->save($pathFull);
                $data['images'][] = $fileName;
            }
            $data['images'] = json_encode($data['images']);
        }

        Product::create($data);

        return redirect()->route('frontend.myProduct')->with('success', 'Product has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
