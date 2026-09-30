<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Requests\ProductRequest\ProductRequest;
use App\Http\Requests\ProductRequest\ProductRequest as RequestsProductRequest;

class ProductController extends BaseController
{
    public function myProduct()
    {
        $userId = Auth::id();
        $products = Product::where('user_id', $userId)->get();
        // dd($products->toArray());
        return $this->sendResponse(ProductResource::collection($products), 'Retrieved my product successfully!');
    }

    public function store(RequestsProductRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('images')) {
            $data['images'] = [];
            // 1. Định nghĩa các đường dẫn thư mục
            $pathSmallFolder  = public_path('upload/product/small');
            $pathMediumFolder = public_path('upload/product/medium');
            $pathFullFolder   = public_path('upload/product/full');

            // 2. Tự động kiểm tra & tạo thư mục nếu chưa có (phân quyền 0755, tạo cả cây thư mục cha)
            File::ensureDirectoryExists($pathSmallFolder);
            File::ensureDirectoryExists($pathMediumFolder);
            File::ensureDirectoryExists($pathFullFolder);
            foreach ($request->file('images') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $cleanName = str_replace(' ', '_', $fileName);

                $pathSmall = $pathSmallFolder . '/' . $cleanName;
                $pathMedium = $pathMediumFolder . '/' . $cleanName;
                $pathFull = $pathFullFolder . '/' . $cleanName;

                $file->move($pathFullFolder, $cleanName);
                Image::read($pathFull)->resize(256, 190)->save($pathMedium);
                Image::read($pathFull)->resize(85, 85)->save($pathSmall);

                //Đã khai báo thuộc tính casts trong model nên khi lưu db $data['images'] sẽ tự động chuyển json trước khi thêm db
                $data['images'][] = $cleanName;
            }
        }

        $product = Product::create($data);

        return $this->sendResponse(new ProductResource($product), 'Product has been created successfully!');
    }

    public function show() {}
}
