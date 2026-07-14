<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::paginate(3);
        return view('admin.blog.list', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $fileMoved = false;
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('upload/blog/title'), $fileName);
            $fileMoved = true;

            $data['image'] = 'upload/blog/title/' . $fileName;
        }

        try {
            Blog::Create($data);
            return redirect()->route('admin.blog.index')->with('success', 'Blog has created successfully!');
        } catch (\Exception $e) {
            if ($fileMoved) {
                unlink(public_path($data['image']));
            }
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $file = null;
        $fileName = null;
        $hasNewFile = $request->hasFile('image');

        $data = $request->validated();
        $oldImage = public_path($blog->image);
        if ($hasNewFile) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $data['image'] = 'upload/blog/title/' . $fileName;
        }
        if ($blog->update($data)) {
            if ($hasNewFile) {
                $file->move(public_path('upload/blog/title'), $fileName);

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            return redirect()->route('admin.blog.index')->with('success', 'Edit blog successfully!');
        } else {
            return redirect()->back()->with('errors', 'Edit Blog unsuccessfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->image && file_exists(public_path($blog->image))) {
            unlink(public_path($blog->image));
        }
        if ($blog->delete()) {
            return redirect()->route('admin.blog.index')->with('success', 'Delete blog successfully!');
        }
        return redirect()->back()->with('errors', 'Delete blog unsuccessfully!');
    }
}
