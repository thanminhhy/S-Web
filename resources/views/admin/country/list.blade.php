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
                            <th class="border-top-0">Country Name</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($countries->count())
                        @foreach($countries as $country)
                        <tr>
                            <td>
                                {{$country->id}}
                            </td>
                            <td>
                                {{$country->name}}
                            </td>
                            <td>
                                <div class='d-flex'>
                                    <!-- <a href="" class='btn btn-warning btn-sm mr-2'>Edit</a> -->
                                    <div>
                                        <button class='btn btn-warning btn-sm mr-2'
                                            type='button'
                                            data-toggle='modal'
                                            data-target='#editCountryModal{{$country->id}}'>
                                            Edit
                                        </button>
                                        <div class="modal fade" id="editCountryModal{{$country->id}}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">

                                                <form action="{{route('admin.country.update',$country->id)}}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Country</h5>

                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <label>Country Name</label>
                                                                <input type="text"
                                                                    name="name"
                                                                    class="form-control"
                                                                    placeholder="Enter the country name"
                                                                    value="{{$country->name}}">
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <form action="{{route('admin.country.delete',$country->id)}}" method="POST">
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
            <div class=" col-sm-12">
                <button type="button"
                    class="btn btn-success"
                    data-toggle="modal"
                    data-target="#addCountryModal">
                    Add Country
                </button>
                <div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">

                        <form action="{{route('admin.country.store')}}" method="POSt">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Country</h5>

                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label>Country Name</label>
                                        <input type="text"
                                            name="name"
                                            class="form-control"
                                            placeholder="Enter the country name">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit"
                                        class="btn btn-success">Save</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <div class='mt-3'>
                {{$countries->onEachSide(0)->links()}}
            </div>
        </div>
    </div>
</div>
@endsection