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
                                        <button class='btn btn-warning btn-sm mr-2'
                                            type='button'
                                            data-toggle='modal'
                                            data-target='#editProductModal{{$product->id}}'>
                                            Edit
                                        </button>
                                        <div class="modal fade" id="editProductModal{{$product->id}}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">

                                                <form id="" class="ajax-update-user-form" action="" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update User</h5>

                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>User Name</label>
                                                                <input type="text"
                                                                    name="name"
                                                                    class="form-control"
                                                                    placeholder="Enter the user name"
                                                                    value="">
                                                                <div class="invalid-feedback error-name"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="text"
                                                                    name="email"
                                                                    class="form-control"
                                                                    placeholder="Enter the user email"
                                                                    value="">
                                                                <div class="invalid-feedback error-email"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Phone</label>
                                                                <input type="text"
                                                                    name="phone"
                                                                    class="form-control"
                                                                    placeholder="Enter the user phone"
                                                                    value="">
                                                                <div class="invalid-feedback error-phone"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Address</label>
                                                                <input type="text"
                                                                    name="address"
                                                                    class="form-control"
                                                                    placeholder="Enter the user address"
                                                                    value="">
                                                                <div class="invalid-feedback error-address"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <div class="invalid-feedback error-id_country"></div>
                                                            </div>
                                                        </div>
                                                        <div class=" modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
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