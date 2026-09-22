<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Brand;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showUserShoppingHistory()
    {
        $orders = Order::paginate(6);
        return view('admin.order_history.OrderHistory', compact('orders'));
    }
    public function showListProduct()
    {
        $products = Product::paginate(6);
        return view('admin.product.listProduct', compact('products'));
    }

    public function showOrderItems(string $orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        return response()->json([
            'order' => $order,
            'items' => $order->items
        ], 200);
    }

    public function showEditProduct(string $productId)
    {
        $product = Product::findOrFail($productId);
        $categories = Category::get();
        $brands = Brand::get();

        return view('admin.product.editProduct', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(ProductRequest $request, Product $product)
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
            return redirect()->back()->withInput()
                ->withErrors(['images' => 'Tổng số lượng ảnh(ảnh cũ giữ lại + ảnh mới) không được quá 3 hình']);
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
        return redirect()->route('admin.showListProduct')->with('success', 'Update product successfully!');
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
