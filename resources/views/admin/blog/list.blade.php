@extends('admin.layouts.app')
@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border-top-0">#</th>
                            <th class="border-top-0">Title</th>
                            <th class="border-top-0">Image</th>
                            <th class="border-top-0">Description</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($blogs->count())
                        @foreach($blogs as $blog)
                        <tr>
                            <td>
                                {{$blog->id}}
                            </td>
                            <td>
                                {{$blog->title}}
                            </td>
                            <td>
                                <img src="{{asset($blog->image)}}" width="150px" height="150px">
                            </td>
                            <td>
                                {{$blog->description}}
                            </td>
                            <td>
                                <div class='d-flex'>
                                    <!-- <a href="" class='btn btn-warning btn-sm mr-2'>Edit</a> -->
                                    <div>
                                        <form action=" {{route('admin.blog.edit',$blog->id)}}">
                                            <button class='btn btn-warning btn-sm mr-2'>Edit</button>
                                        </form>
                                    </div>
                                    <div>
                                        <form action="{{route('admin.blog.delete',$blog->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class='btn btn-danger btn-sm'>Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class='mt-3'>
                <form action="{{route('admin.blog.create')}}">
                    <button class="btn btn-success">Add blog</button>
                </form>
            </div>
            <div class="mt-3">
                {{$blogs->onEachSide(0)->links()}}
            </div>
        </div>
    </div>
</div>
@endsection