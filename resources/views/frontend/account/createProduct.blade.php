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

<div class='signup-form'>
    <h2 class="title text-center">Add New Product</h2>
    <div>
        <form method="POST" enctype="multipart/form-data" action="{{route('frontend.createProduct')}}">
            @csrf
            <div class="row mb-3 align-items-center">
                <input type="text" name="name" placeholder="Name" value="" />
                @error('name')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <input type="text" name="price" placeholder="Price" value="" />
                @error('price')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <select name="status" id="product-status" class="form-control">
                    <option value="">-----Please choose product status-----</option>
                    <option value="new">New</option>
                    <option value="sale">Sale</option>
                </select>
                @error('status')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center sale-group" style="display:none;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <input type="text"
                        name="sale"
                        placeholder="Sale"
                        value=""
                        style="width: 120px; margin: 0" />
                    <span>%</span>
                </div>
                @error('sale')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <select name="category_id" id="category_id">
                    <option value="">-----Please choose category of product-----</option>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <select name="brand_id" id="brand_id">
                    <option value="">-----Please choose brand of product-----</option>
                    @foreach($brands as $brand)
                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                    @endforeach
                </select>
                @error('brand_id')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>

            <div class="row mb-3 align-items-center">
                <input type="text" name="compnay" placeholder="Company Profile" value="" />
                @error('company')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-sm-10" id="preview-container"></div>
                <input type="file"
                    name="images[]"
                    id="images"
                    accept="image/png, image/jpg, image/jpeg, image/webp"
                    multiple />
                @error('images')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center">
                <textarea placeholder="Detail" name="detail"></textarea>
                @error('detail')
                <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <div class="row mb-3 align-items-center" style="margin-bottom: 10px;">
                <button type="submit">Add</button>
            </div>
        </form>
    </div>

</div>
@endsection
@push('scripts')
<script src="{{asset('frontend/js/product-form.js')}}"></script>
@endpush