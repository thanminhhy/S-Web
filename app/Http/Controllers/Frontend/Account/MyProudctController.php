<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
                Image::read($pathFull)->resize(120, 120)->save($pathMedium);
                Image::read($pathFull)->resize(50, 70)->save($pathSmall);

                $data['images'][] = $cleanName;
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
    public function edit(Product $product)
    {
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontend.account.editProduct', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        //1. Lấy danh sách images từ db
        $listImages = json_decode($product->images, true) ?? [];
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
                Image::read($pathFull)->resize(120, 120)->save($pathMedium);
                Image::read($pathFull)->resize(50, 70)->save($pathSmall);

                $listImages[] = $cleanName;
            }
        }
        // $data['images'] = $listImages;
        $data['images'] = json_encode(array_values($listImages));
        $product->update($data);
        return redirect()->route('frontend.myProduct')->with('success', 'Update product successfully!');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
