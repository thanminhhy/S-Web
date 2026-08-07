<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Blog\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\Account\ProfileController;
use App\Http\Controllers\Frontend\Account\MyProudctController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [HomeController::class, 'index']);

Auth::routes();

//============Admin===========

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

//=========Frontend=========
Route::get('/frontend/index', [FrontendHomeController::class, 'index'])->name('frontend.index');

//login
Route::get('/frontend/login', [LoginCOntroller::class, 'showLogin'])->name('frontend.login');
Route::post('/frontend/login', [LoginController::class, 'login'])->name('frontend.login');

//Logout
Route::post('/frontend/logout', [LoginController::class, 'logout'])->name('frontend.logout');

//Register
Route::get('/frontend/register', [Registercontroller::class, 'showRegister'])->name('frontend.register');
Route::post('/frontend/register', [RegisterController::class, 'register'])->name('frontend.register');

//Blog
Route::get('/frontend/blog/index', [FrontendBlogController::class, 'index'])->name('frontend.blog.list');
Route::get('/frontend/blog/detail/{blog}', [FrontendBlogController::class, 'showDetail'])->name('frontend.blog.detail');
Route::middleware(['auth'])->group(function () {
    Route::post('/frontend/blog/rate/ajax', [FrontendBlogController::class, 'rate'])->name('blog.rate');
});
//--blog comment--
Route::middleware(['auth'])->group(function () {
    Route::post('/frontend/blog/comment/ajax', [FrontendBlogController::class, 'comment'])->name('blog.comment');
});

//Acount Management
Route::middleware(['auth'])->group(function () {
    Route::get('/frontend/myAccount', [ProfileController::class, 'showProfile'])->name('frontend.myAccount');
});
Route::middleware(['auth'])->group(function () {
    Route::post('/frontend/myAccount/{user}', [ProfileController::class, 'updateProfile'])->name('frontend.updateAccount');
});

//CRUD My Product
Route::middleware(['auth'])->group(function () {
    Route::get('/frontend/myAccount/myProduct', [MyProudctController::class, 'index'])->name('frontend.myProduct');
});
Route::middleware(['auth'])->group(function () {
    Route::post('/frontend/myAccount/myProduct/store', [MyProudctController::class, 'store'])->name('frontend.createProduct');
});
