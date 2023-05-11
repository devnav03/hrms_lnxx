@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Employee Type Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('emp-type-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required>
                                        @if(!empty($office))
                                            <option value="">Select Office</option>
                                            @foreach($office as $office)
                                                <option value="{{$office->id}}" @if(!empty($update->office_id)) @if($update->office_id==$office->id) selected @endif @endif>{{$office->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Employee Type Name *</label>
                                    <input type="text" class="form-control" id="emp_type" name="emp_type" value="@if(!empty($update->emp_type)){{$update->emp_type}}@endif" placeholder="Enter Employee Type Name" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Type List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office Name</th>
                                    <th>Employee Type Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-emp-type-master')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'office_name'},
                {data:'emp_type'},

                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.created_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.updated_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("emp-type-master")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("emp-type-master-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
</script>
@endsection('content')