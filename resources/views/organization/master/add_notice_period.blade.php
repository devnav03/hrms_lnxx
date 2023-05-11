@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Notice Period</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-notice-period')}}" method="POST">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id();">
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department *</label>
                                    <select class="form-control" id="department_id" name="department_id" required onchange="get_designation();">
                                        @if(!empty($department))
                                            <option value="">--Select--</option>
                                            @foreach($department as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Designation *</label>
                                    <select class="form-control" id="position_id" name="position_id" required>
                                        @if(!empty($position))
                                            <option value="">--Select--</option>
                                            @foreach($position as $row2)
                                                <option value="{{$row2->id}}" data-id="{{$row2->id}}" @if(!empty($update->position_id)) @if($update->position_id==$row2->id) selected @endif @endif>{{$row2->position_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Notice Days *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="number" class="form-control" id="notice_days" name="notice_days" value="@if(!empty($update->notice_days)){{$update->notice_days}}@endif" placeholder="Enter Notice Days" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
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
                                <h5 class="" id="getCameraSerialNumbers">Notice List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Notice</th>
                                    <th>Default</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are You Sure Want to Delete?</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-footer">
                <span id="delte_id"></span>
                <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
            </div>
            </div>
            
        </div>
    </div>

    <script>
        function get_office_id() {
            var department_id = $('#office_id option:selected').data('id');
            $('#department_id').empty();
            $('#designation_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-department-name')}}",
                data: {
                    department_id: department_id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }
                }
            });
        }
        function get_designation() {
            var office_id = $('#office_id option:selected').data('id');
            var department_id = $('#department_id option:selected').data('id');
            $('#position_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-designation')}}",
                data: {
                    office_id: office_id,
                    department_id: department_id,
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#position_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                    }
                }
            });
        }
    </script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-notice-period')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'office_name'},
                {data:'department_name'},
                {data:'position_name'},
                {data:'notice_days'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.is_default=='Yes'){
                            return '<td><i data="'+data.id+'" id="indss'+data.id+'" class="default_checks btn-xs btn btn-outline-success">Yes</i></td>';
                        }else{
                            return '<td><i data="'+data.id+'" id="indss'+data.id+'" class="default_checks btn-xs btn btn-outline-danger">No</i></td>';
                        }
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.status=='Active'){
                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-success">Active</i></td>';
                        }else{
                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-danger">Inactive</i></td>';
                        }
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("add-notice-period")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("notice-period-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ]
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
    $(document).on('click','.default_checks',function(){
        var is_default = ($(this).hasClass("btn-outline-success")) ? 'No' : 'Yes';
        var msg = (is_default=='Yes')? 'Yes' : 'No';
        var current_element = $(this);
        swal({
            title: "Are you sure?",
            text: "Do you want to change status "+msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change status!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/get-default-notice')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),is_default:is_default},
                    success: function(xhr){
                    if(xhr.data.is_default=='No'){
                        $('#indss'+xhr.data.id).addClass('btn-outline-danger');
                        $('#indss'+xhr.data.id).removeClass('btn-outline-success');
                        $('#indss'+xhr.data.id).text('No');
                        // toastr.success("Default updated to 'No'");
                    }else{
                        $('#indss'+xhr.data.id).addClass('btn-outline-success');
                        $('#indss'+xhr.data.id).removeClass('btn-outline-danger');
                        $('#indss'+xhr.data.id).text('Yes');
                        // toastr.success("Default updated to 'Yes'");
                    }
                    swal(xhr.data.is_default, "Succesfully "+xhr.data.is_default, "success");
                },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Error deleting!", "Please try again", "error");
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).on('click','.status_checks',function(){
        var status = ($(this).hasClass("btn-outline-success")) ? 'Inactive' : 'Active';
        var msg = (status=='Active')? 'Active' : 'Inactive';
        var current_element = $(this);
        swal({
            title: "Are you sure?",
            text: "Do you want to change status "+msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change status!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/get-status-notice')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),status:status},
                    success: function(xhr){
                        if(xhr.data.status=='Inactive'){
                            $('#inds'+xhr.data.id).addClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-success');
                            $('#inds'+xhr.data.id).text('Inactive');
                            // toastr.success("Inactive");
                        }else{
                            $('#inds'+xhr.data.id).addClass('btn-outline-success');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).text('Active');
                            // toastr.success("Active");
                        }
                        swal(xhr.data.status, "Succesfully "+xhr.data.status, "success");
                        
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Error deleting!", "Please try again", "error");
                    }
                });
            }
        });
    });
</script>
@endsection('content')