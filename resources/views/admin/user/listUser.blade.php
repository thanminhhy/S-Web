@extends('admin.layouts.app')
@section('content')
{{-- Alert Thông báo thành công --}}
@if (session('status'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('status') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border-top-0">ID</th>
                            <th class="border-top-0">Name</th>
                            <th class="border-top-0">Email</th>
                            <th class="border-top-0">Phone</th>
                            <th class="border-top-0">Address</th>
                            <th class="border-top-0">Country</th>
                            <th class="border-top-0">Role</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->count())
                        @foreach($users as $user)
                        <tr id="user-row-{{$user->id}}">
                            <td>
                                {{$user->id}}
                            </td>
                            <td class="user-name">
                                {{$user->name ?? 'N/A'}}
                            </td>
                            <td class="user-email">
                                {{$user->email ?? 'N/A'}}
                            </td>
                            <td class="user-phone">
                                {{$user->phone ?? 'N/A'}}
                            </td>
                            <td class="user-address">
                                {{$user->address ?? 'N/A'}}
                            </td>
                            <td class="user-country">
                                {{$user->country?->name ?? 'N/A'}}
                            </td>
                            <td class="role">
                                {{$user->level === 1 ? 'Admin' : 'User'}}
                            </td>
                            <td>
                                <div class='d-flex'>
                                    <!-- <a href="" class='btn btn-warning btn-sm mr-2'>Edit</a> -->
                                    <div>
                                        <button class='btn btn-warning btn-sm mr-2'
                                            type='button'
                                            data-toggle='modal'
                                            data-target='#editUserModal{{$user->id}}'>
                                            Edit
                                        </button>
                                        <div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">

                                                <form id="updateUserForm_{{$user->id}}" class="ajax-update-user-form" action="{{route('admin.updateUser',$user->id)}}" method="POST">
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
                                                                    value="{{old('name',$user->name)}}">
                                                                <div class="invalid-feedback error-name"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="text"
                                                                    name="email"
                                                                    class="form-control"
                                                                    placeholder="Enter the user email"
                                                                    value="{{$user->email}}">
                                                                <div class="invalid-feedback error-email"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Phone</label>
                                                                <input type="text"
                                                                    name="phone"
                                                                    class="form-control"
                                                                    placeholder="Enter the user phone"
                                                                    value="{{$user->phone}}">
                                                                <div class="invalid-feedback error-phone"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Address</label>
                                                                <input type="text"
                                                                    name="address"
                                                                    class="form-control"
                                                                    placeholder="Enter the user address"
                                                                    value="{{$user->address}}">
                                                                <div class="invalid-feedback error-address"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <select class="form-control" name="id_country" id="id_country">
                                                                    <option value="">--------Choose a Country--------</option>
                                                                    @foreach($countries as $country)
                                                                    <option value="{{$country->id}}" {{old('id_country',$user->id_country) == $country->id ? 'selected' : ''}}>{{$country->name}}</option>
                                                                    @endforeach
                                                                </select>
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
                                        <form action="" method="POST">
                                            @csrf
                                            @method('DELETE')

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
                {{$users->onEachSide(0)->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('listUserScript')
<script src="{{asset('admin/js/usermanagement.js')}}"></script>
@endpush