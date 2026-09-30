<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\BlogRating;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BlogResource;

class BlogController extends BaseController
{
    //Display a listing of the resource
    public function index(): JsonResponse
    {
        $blogs = Blog::all();

        return $this->sendResponse(BlogResource::collection($blogs), 'Blogs retrieved successfully!');
    }

    //Store a newly created resource in storage
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $blog = Blog::create($input);

        return $this->sendResponse(new BlogResource($blog), 'Blog created successfully.');
    }

    // Display the specified resource.
    public function show($id): JsonResponse
    {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            return $this->sendError('Blog not found.');
        }

        return $this->sendResponse(new BlogResource($blog), 'Blog retrieved successfully.');
    }

    // Update the specifed resource in storage
    public function update(Request $request, Blog $blog): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $blog->title = $input['title'];
        $blog->description = $input['description'];
        $blog->save();

        return $this->sendResponse(new BlogResource($blog), 'Blog updated successfully.');
    }

    public function destroy(Blog $blog): JsonResponse
    {
        $blog->delete();

        return $this->sendResponse([], 'Blog deleted successfully!');
    }

    public function comment(Request $request, Blog $blog)
    {
        //Check is user logged in
        if (!Auth::Check()) {
            return $this->sendError('Authentication Failed', [
                'message' => 'Bạn cần đăng nhập để có thể thực hiện bình luận!'
            ], 401);
        }
        //Validate request
        $request->validate([
            'comment' => 'required',
            'parent_id' => 'nullable|integer'
        ]);

        //declare some necessary varibles
        $userId = Auth::id();
        $comment = $request->comment;
        $parentId = $request->parent_id ?? null;
        // dd($userId, $blogId, $comment, $parentId);

        //Save comment to database
        $cmtData = Comment::Create(
            [
                'blog_id' => $blog->id,
                'user_id' => $userId,
                'parent_id' => $parentId,
                'comment' => $comment
            ]
        );

        $cmtData->load('user');

        return $this->sendResponse([
            'status' => 'success',
            'message' => 'Bình luận bài viết thành công!',
            'data' => $cmtData,
            'formatedTime' => $cmtData->created_at->format('h:i A'),
            'formatedDate' => $cmtData->created_at->format('d/m/Y')
        ], 'Comment Successfully!');
    }

    public function rate(Request $request, Blog $blog)
    {
        //Check is user logged in
        if (!Auth::check()) {
            return $this->sendError('Authentication failed', [
                'message' => 'Bạn cần đăng nhập để có thể thực hiện đánh giá!'
            ], 401);
        }

        //validate request
        $request->validate([
            'rate' => 'required|integer|min:1|max:5'
        ]);
        $userId = Auth::id();
        $ratingValue = $request->rate;

        BlogRating::updateOrCreate(
            [
                'blog_id' => $blog->id,
                'user_id' => $userId,
            ],
            [
                'rating' => $ratingValue,
            ]
        );

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
