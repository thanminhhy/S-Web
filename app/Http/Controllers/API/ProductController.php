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

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return $this->sendResponse(new ProductResource($product), 'Retrieved product detail successfully!');
    }

    public function update(ProductRequest $request, Product $product)
    {
        //1. Lấy danh sách images từ db
        $listImages = $product->images ?? [];
        $data = $request->validated();

        //2. Check xem có hình cần xóa khi udpate không để xử lí
        if ($request->has('hinhxoa')) {
            foreach ($request->hinhxoa as $imageToDelete) {
                if (($key = array_search($imageToDelete, $listImages)) !== false) {
                    //xóa file vật lý bằng hàm tự viết
                    $this->deletePhysicalImages($listImages[$key]);

                    //xóa tên file khỏi mảng theo vị trí index tìm ra tại $key
                    unset($listImages[$key]);
                }
            }
            //Sắp xếp lại thứ tự mảng
            $listImages = array_values($listImages);;
        }

        //Đếm số lượng file ảnh thêm mới
        $newImagesCount = $request->hasFile('images') ? count($request->file('images')) : 0;

        //3. Kiểm tra nếu số lượng ảnh cũ và mới có quá 3 ảnh không
        if (count($listImages) + $newImagesCount > 3) {
            return $this->sendError('Validation Error.', ['images' => 'Tổng số lượng ảnh(ảnh cũ giữ lại + ảnh mới) không được quá 3 hình'], 422);
        }

        //4. Kiểm tra có thêm ảnh mới không để xử lí thêm vào db
        if ($request->hasFile('images')) {
            $pathSmallFolder = public_path('upload/product/small');
            $pathMediumFolder = public_path('upload/product/medium');
            $pathFullFolder = public_path('upload/product/full');

            foreach ($request->file('images') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $cleanName = str_replace(' ', '_', $fileName);

                $pathSmall = $pathSmallFolder . '/' . $cleanName;
                $pathMedium = $pathMediumFolder . '/' . $cleanName;
                $pathFull = $pathFullFolder . '/' . $cleanName;

                $file->move($pathFullFolder, $cleanName);
                Image::read($pathFull)->resize(256, 190)->save($pathMedium);
                Image::read($pathFull)->resize(85, 85)->save($pathSmall);

                $listImages[] = $cleanName;
            }
        }
        // $data['images'] = $listImages;
        $data['images'] = array_values($listImages);
        $product->update($data);
        return $this->sendResponse(new ProductResource($product), 'Product update successfully!');
    }

    private function deletePhysicalImages($fileName)
    {
        $folders = ['full', 'medium', 'small'];
        foreach ($folders as $folder) {
            $filePath = public_path("upload/product/{$folder}/{$fileName}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
