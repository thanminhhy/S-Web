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
                            <th class="border-top-0">User Name</th>
                            <th class="border-top-0">User Email</th>
                            <th class="border-top-0">User Phone</th>
                            <th class="border-top-0">User Address</th>
                            <th class="border-top-0">Total Price Order</th>
                            <th class="border-top-0">Order Status</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->count())
                        @foreach($orders as $order)
                        <tr id="user-row-{{$order->id}}">
                            @csrf
                            <td>
                                {{$order->id}}
                            </td>
                            <td>
                                {{$order->user?->name}}
                            </td>
                            <td>
                                {{$order->email}}
                            </td>
                            <td>
                                {{$order->phone}}
                            </td>
                            <td>
                                {{$order->address}}
                            </td>
                            <td>
                                {{$order->total_price}} VND
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $order->status }}</span>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm btn-view-items"
                                    data-id="{{$order->id}}"
                                    data-url="{{route('admin.order.detail',$order->id)}}">
                                    View Items
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class='mt-3'>
                {{$orders->onEachSide(0)->links()}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderItemsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details #<span id="modal-order-id"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-list">
                        <!-- JS sẽ đổ dữ liệu items vào đây -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('Order_AdminScript')
<script src="{{asset('/admin/js/order.js')}}"></script>
@endpush