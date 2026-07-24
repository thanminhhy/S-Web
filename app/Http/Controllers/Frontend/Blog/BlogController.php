<?php

namespace App\Http\Controllers\Frontend\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\BlogRating;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(3);
        return view('frontend.blog.index', compact('blogs'));
    }

    public function showDetail(Blog $blog)
    {
        $userRating = 0;
        if (Auth::check()) {
            $rating = BlogRating::where('blog_id', $blog->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($rating) {
                $userRating = $rating->rating;
            }
        }
        // dd($userRating);
        return view('frontend.blog.detail', compact('blog', 'userRating'));
    }
    public function rate(Request $request)
    {
        //Check is user logged in
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để có thể thực hiện đánh giá!'
            ], 401);
        }

        //validate request
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'rate' => 'required|integer|min:1|max:5'
        ]);
        $userId = Auth::id();
        $blogId = $request->blog_id;
        $ratingValue = $request->rate;

        BlogRating::updateOrCreate(
            [
                'blog_id' => $blogId,
                'user_id' => $userId,
            ],
            [
                'rating' => $ratingValue,
            ]
        );

        $blog = Blog::findOrFail($blogId);

        $ratingCount = $blog->ratings()->count();
        $ratingAvg = round($blog->ratings()->avg('rating'), 1);

        $blog->update([
            'rating_count' => $ratingCount,
            'rating_avg' => $ratingAvg,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đánh giá bài viết thành công!',
            'rating_count' => $ratingCount,
            'rating_avg' => $ratingAvg
        ]);
    }
}
