@extends('admin.layouts.app')
@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border-top-0">ID</th>
                            <th class="border-top-0">Product Name</th>
                            <th class="border-top-0">Price</th>
                            <th class="border-top-0">Status</th>
                            <th class="border-top-0">Sale</th>
                            <th class="border-top-0">Images</th>
                            <th class="border-top-0">Company</th>
                            <th class="border-top-0">Brand</th>
                            <th class="border-top-0">Category</th>
                            <th class="border-top-0">Owner</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->count())
                        @foreach($products as $product)
                        <tr id="user-row-{{$product->id}}">
                            @csrf
                            <td>
                                {{$product->id}}
                            </td>
                            <td>
                                {{$product->name}}
                            </td>
                            <td>
                                {{$product->price}} VND
                            </td>
                            <td>
                                {{$product->status}}
                            </td>
                            <td>
                                {{$product->sale}}%
                            </td>
                            <td>
                                <img src="{{asset('/upload/product/small/'.$product->images[0])}}">

                            </td>
                            <td>
                                {{$product->company}}
                            </td>
                            <td>
                                {{$product->brand?->name}}
                            </td>
                            <td>
                                {{$product->category?->name}}
                            </td>
                            <td>
                                {{$product->user?->name}}
                            </td>
                            <td>
                                <div class='d-flex'>
                                    <!-- <a href="" class='btn btn-warning btn-sm mr-2'>Edit</a> -->
                                    <div>
                                        <a href="{{route('admin.showEditProduct', $product->id) }}"
                                            class="btn btn-warning btn-sm mr-2">
                                            Edit
                                        </a>
                                    </div>
                                    <div>
                                        <form class="ajax-delete-user-form" action="" method="POST">
                                            @csrf

                                            <button type="submit"
                                                class='btn btn-danger btn-sm'>Delete</button>
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
                {{$products->onEachSide(0)->links()}}
            </div>
        </div>
    </div>
</div>
@endsection