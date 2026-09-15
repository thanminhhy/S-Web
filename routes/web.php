<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Blog\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\Account\ProfileController;
use App\Http\Controllers\Frontend\Account\MyProudctController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\Admin;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [HomeController::class, 'index']);


//Modify Auth created by laravel for admin
Route::prefix('admin')->name('admin.')->group(function () {
    //log in - log out
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    //Forget password
    Route::get('pasword/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    //Reset password
    Route::post('password/reset', [AdminResetPasswordController::class, 'reset'])->name('password.update');
});

Route::prefix('admin')->group(function () {
    //Reset password
    Route::get('password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
});

//============Admin===========

Route::middleware(['admin'])->group(function () {
    //Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    //Profile
    Route::get('/admin/profile', [UserController::class, 'index'])->name('admin.profile');
    Route::post('/admin/update/{id}', [UserController::class, 'update']);

    //Country
    Route::get('/admin/country', [CountryController::class, 'index'])->name('admin.country');
    Route::post('/admin/country/store', [CountryController::class, 'store'])->name('admin.country.store');
    Route::post('/admin/country/update/{id}', [CountryController::class, 'update'])->name('admin.country.update');
    Route::delete('/admin/country/delete/{id}', [CountryController::class, 'destroy'])->name('admin.country.delete');

    //Blog
    Route::get('/admin/blog/index', [BlogController::class, 'index'])->name('admin.blog.index');
    Route::get('/admin/blog/create', [BlogController::class, 'create'])->name('admin.blog.create');
    Route::post('/admin/blog/store', [BlogController::class, 'store'])->name('admin.blog.store');
    Route::get('/admin/blog/edit/{blog}', [BlogController::class, 'edit'])->name('admin.blog.edit');
    Route::post('/admin/blog/update/{blog}', [BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('/admin/blog/delete/{blog}', [BlogController::class, 'destroy'])->name('admin.blog.delete');
});


//=========Frontend=========
Route::get('/frontend/index', [FrontendHomeController::class, 'index'])->name('frontend.index');

//login
Route::get('/frontend/login', [LoginController::class, 'showLogin'])->name('frontend.login');
Route::post('/frontend/login', [LoginController::class, 'login'])->name('frontend.login');

//Logout
Route::post('/frontend/logout', [LoginController::class, 'logout'])->name('frontend.logout');

//Register
Route::get('/frontend/register', [Registercontroller::class, 'showRegister'])->name('frontend.register');
Route::post('/frontend/register', [RegisterController::class, 'register'])->name('frontend.register');

//Blog
Route::get('/frontend/blog/index', [FrontendBlogController::class, 'index'])->name('frontend.blog.list');
Route::get('/frontend/blog/detail/{blog}', [FrontendBlogController::class, 'showDetail'])->name('frontend.blog.detail');
Route::middleware(['member'])->group(function () {
    //Blog rating
    Route::post('/frontend/blog/rate/ajax', [FrontendBlogController::class, 'rate'])->name('blog.rate');
    //Blog comment
    Route::post('/frontend/blog/comment/ajax', [FrontendBlogController::class, 'comment'])->name('blog.comment');
});

//Acount Management
Route::middleware(['member'])->group(function () {
    Route::get('/frontend/myAccount', [ProfileController::class, 'showProfile'])->name('frontend.myAccount');
    Route::post('/frontend/myAccount/{user}', [ProfileController::class, 'updateProfile'])->name('frontend.updateAccount');
});

//CRUD My Product
Route::middleware(['member'])->group(function () {
    Route::get('/frontend/myAccount/myProduct', [MyProudctController::class, 'index'])->name('frontend.myProduct');
    Route::get('/frontend/myAccount/myProduct/CreateProduct', [MyProudctController::class, 'create'])->name('frontend.showCreateProductForm');
    Route::post('/frontend/myAccount/myProduct/store', [MyProudctController::class, 'store'])->name('frontend.createProduct');
    Route::get('/frontend/myAccount/myProduct/EditProduct/{product}', [MyProudctController::class, 'Edit'])->name('frontend.showEditProductForm');
    Route::post('/frontend/myAccount/myProduct/EditProduct/{product}', [MyProudctController::class, 'Update'])->name('frontend.updateProduct');
});
Route::get('/frontend/product/detail/{product}', [ProductController::class, 'showProductDetail'])->name('frontend.product.detail');

//Cart
Route::middleware(['member'])->group(function () {
    Route::post('/frontend/cart/addProduct', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/frontend/cart/index', [CartController::class, 'showCart'])->name('cart.index');
    Route::post('/frontend/cart/updateQuantity', [CartController::class, 'updateCartQuantity'])->name('cart.updateQty');

    Route::get('/frontend/cart/checkout', [Cartcontroller::class, 'showCheckoutForm'])->name('frontend.cart.checkout');
    Route::post('/frontend/cart/checkout', [Cartcontroller::class, 'processCheckout'])->name('frontend.cart.checkout.process');
});

Route::get('/test', [CartController::class, 'previewMail']);


//Search
Route::get('/api/live-search', [SearchController::class, 'liveSearch'])->name('liveSearch');
Route::get('/api/search', [SearchController::class, 'search'])->name('frontend.search');
