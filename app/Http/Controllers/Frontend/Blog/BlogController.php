<?php

namespace App\Http\Controllers\Frontend\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\BlogRating;
use App\Models\Comment;

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

            // $comments = Comment::where('blog_id', $blog->id)
            //     ->where('parent_id',)
            //     ->first();
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

    public function comment(Request $request)
    {
        //Check is user logged in
        if (!Auth::Check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để có thể thực hiện bình luận!'
            ], 401);
        }
        //Validate request
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required',
            'parent_id' => 'nullable|integer'
        ]);

        //declare some necessary varibles
        $userId = Auth::id();
        $blogId = $request->blog_id;
        $comment = $request->comment;
        $parentId = $request->parent_id ?? null;
        // dd($userId, $blogId, $comment, $parentId);

        //Save comment to database
        $cmtData = Comment::Create(
            [
                'blog_id' => $blogId,
                'user_id' => $userId,
                'parent_id' => $parentId,
                'comment' => $comment
            ]
        );

        $cmtData->load('user');

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận bài viết thành công!',
            'data' => $cmtData,
            'formatedTime' => $cmtData->created_at->format('h:i A'),
            'formatedDate' => $cmtData->created_at->format('d/m/Y')
        ]);
    }
}
