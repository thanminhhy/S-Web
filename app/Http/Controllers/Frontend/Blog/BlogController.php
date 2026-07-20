<?php

namespace App\Http\Controllers\Frontend\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(3);
        return view('frontend.blog.index', compact('blogs'));
    }

    public function showDetail(Blog $blog)
    {
        $html_content = $blog->content;

        // dd($html_content);
        return view('frontend.blog.detail', compact('blog'));
    }
}
