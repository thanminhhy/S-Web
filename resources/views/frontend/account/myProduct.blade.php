@extends('frontend.layouts.app')
@section('menu-left')
<div class="left-sidebar">
    <h2>Account</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="{{route('frontend.myAccount')}}">Account</a></h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="{{route('frontend.myProduct')}}">My product</a></h4>
            </div>
        </div>

    </div><!--/category-products-->
</div>
@endsection

@section('content')
<div class="table-responsive cart_info">
    <table class="table table-condensed">
        <thead>
            <tr class="cart_menu">
                <td>ID</td>
                <td class="description">name</td>
                <td class="image">image</td>
                <td class="price">price</td>

                <td class="total">action</td>

            </tr>
        </thead>
        <tbody>
            @if($products->isEmpty())
            <tr>
                <td colspan="5" class="text-center">
                    <div class="alert alert-info mb-0">
                        Hiện tại không có sản phẩm nào!
                    </div>
                </td>
            </tr>
            @else
            @foreach($products as $product)
            <tr>
                <td class="cart_product">
                    <a href=""><img src="images/cart/one.png" alt=""></a>
                </td>
                <td class="cart_description">
                    <h4><a href="">Colorblock Scuba</a></h4>

                </td>
                <td class="cart_price">
                    <p>$59</p>
                </td>

                <td class="cart_total">
                    <a>edit</a>
                    <a>delete</a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <div class="mt-3" style="display:flex; justify-content: flex-end; margin-bottom: 10px;">
        <button
            type="button"
            class="btn btn-success"
            data-toggle="modal"
            data-target="#addProductModal">
            Add New Product
        </button>
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">

                <form id="add-product-form" action="{{route('frontend.createProduct')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Product</h5>

                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Enter the product name">
                                @error('name')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10" style="margin-bottom: 10px">
                                    <img
                                        src=""
                                        width='150px'
                                        height='150px'
                                        id="preview"
                                        style="object-fit: cover;">
                                </div>
                                <label>Image</label>
                                <input type="file"
                                    name="image"
                                    id="image"
                                    class="form-control">
                                @error('image')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="text"
                                    name="price"
                                    class="form-control"
                                    placeholder="Enter the product price">
                                @error('price')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="btn btn-success">Save</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<script>
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        console.log(file);

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    })

    //AJAX
    $(document).ready(function() {

        $(document).on('submit', '#add-product-form', function(e) {
            e.preventDefault();
            console.log(123);

        })
    })
</script>
@endsection