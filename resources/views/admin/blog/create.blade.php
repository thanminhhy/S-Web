@extends('admin.layouts.app')
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="card-title fw-bold mb-4 text-dark" style="font-size: 1.25rem;">Create Blog</h5>

        <form action="{{route('admin.blog.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3 align-items-center">
                <label for="title" class="col-sm-2 col-form-label text-secondary">Title<span class="text-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" name="title" id="title">
                    @error('title')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label for="image" class="col-sm-2 col-form-label text-secondary">Image</label>
                <div class="col-sm-10">
                    <input type="file" name="image" id="image">
                    @error('image')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class=" row mb-3 align-items-center">
                <label for="description" class="col-sm-2 col-form-label text-secondary">Description</label>
                <div class="col-sm-10">
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    @error('description')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="content" class="form-label text-secondary fw-medium mb-2">Content</label>
                <textarea name="content" id="editor1" class="form-control">{{ old('content') }}</textarea>
                @error('content')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>

            <div class="text-start">
                <button type="submit" class="btn btn-success px-4" style="background-color: #28a745; border-color: #28a745;">Create Blog</button>
            </div>
        </form>
    </div>
</div>

@endsection