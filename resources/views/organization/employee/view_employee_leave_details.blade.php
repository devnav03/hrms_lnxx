@extends('layouts.organization.app')
@section('content')
<style>
    .lable-primary{
        background-color: #337ab7;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    .lable-success{
        background-color: #5cb85c;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    #leave_data td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Search Employee Leave</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id(this.value);">
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <select class="form-control" id="department_id" name="department_id" onchange="get_department_id(this.value);">
                                        <option value="">--Select--</option>
                                        @if(!empty($department))
                                            @foreach($department as $depa)
                                                <option value="{{$depa->id}}" @if(!empty($update->department_id)) @if($update->department_id==$depa->id) selected @endif @endif>{{$depa->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control" id="user_id" name="user_id">
                                        <option value="">--Select--</option>
                                            @if(!empty($leave_details))
                                                @foreach($leave_details as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Month *</label>
                                    <select class="form-control" id="month" name="month" required>
                                            <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Year *</label>
                                    <select class="form-control" id="year" name="year" required>
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">--Select--</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Reject">Reject</option>
                                    </select>
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
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Leave List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Leave Request</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Emp Code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Leave Type</th>
                                <th scope="col">Start Data</th>
                                <th scope="col">End Data</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Reason</th>
                            </tr>
                        </thead>
                        <tbody id="leave_data">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="leave_id"></div>
            </div>
        </div>
    </div>
    <script>
      $(function () {
        $('form').on('submit', function (e) {
        var spinner = $('#loader');
        spinner.show();
          e.preventDefault();
            $('#example').dataTable().fnClearTable();
            $('#example').dataTable().fnDraw();
            $('#example').dataTable().fnDestroy();
            var datatable = $('#example').dataTable({
                "ajax": function (data, callback, settings) {
                    $.ajax({
                    url: "{{url('ajax/get-employee-leave-data')}}",
                    dataType:"json",
                    type: 'POST',
                    data: $('form').serialize(),
                        success: function(data) {
                            callback(data);
                            spinner.hide();
                        }
                    });
                },
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'employee_code'},
                    {data:'name'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.start_date);
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.end_date);
                        }
                    },
                    {data:'duration'},
                    {data:'leave_type'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            if(data.status=='Pending'){
                                return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="btn-xs btn btn-primary inds'+data.id+'"> Pending</a>';
                            }else if(data.status=='Approved'){
                                return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="btn-xs btn btn-success inds'+data.id+'">Approved</a>';
                            }else if(data.status=='Reject'){
                                return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="btn-xs btn btn-danger inds'+data.id+'">Rejected</a>';
                            }
                        }
                    }
                ]
            });
        });
      });
    </script>
    <script>
        function show_data(id){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/view-emp-leave-leave-data')}}", 
                data: {id:id},
                success:function(xhr){
                    if(xhr.status==200){
                        var html='<tr>'+
                        '<td>'+xhr.data.employee_code+'</td>'+
                        '<td>'+xhr.data.name+'</td>'+
                        '<td>'+xhr.data.leave_type+'</td>'+
                        '<td>'+dateFormate(xhr.data.start_date)+'</td>'+
                        '<td>'+dateFormate(xhr.data.end_date)+'</td>'+
                        '<td>'+xhr.data.duration+' Days</td>'+
                        '<td>'+xhr.data.reason_for_leav_comp+'</td>'+
                        '</tr>';
                        $('#leave_data').html(html);
                        if(xhr.data.status=='Pending'){
                            $('#leave_id').html('<button data="'+xhr.data.id+'"class="status_checks btn btn-success hides btn-sm inds'+xhr.data.id+'">Approve</button>'+
                            '<button data="'+xhr.data.id+'" class="status_checks btn btn-danger btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Reject</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        }if(xhr.data.status=='Approved'){
                            $('#leave_id').html('<button data="'+xhr.data.id+'" class="status_checks btn btn-danger btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Reject</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        }if(xhr.data.status=='Reject'){
                            $('#leave_id').html('<button data="'+xhr.data.id+'" class="status_checks btn btn-success btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Approve</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        }
                        spinner.hide();
                    }
                }
            });
        }
        function get_office_id(id) {
            var spinner = $('#loader');
            spinner.show();
            $('#department_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-department-name')}}",
                data: {
                    department_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }
                    spinner.hide();
                }
            });
        }
        function get_department_id(id) {
            var spinner = $('#loader');
            spinner.show();
            $('#user_id').empty();
            $('#leave_type').empty();
            var office_id = $('#office_id').val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-employee-against-department')}}",
                data: {
                    office_id: office_id,
                    department_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#user_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#user_id').append('<option value="'+datas[i].id+'">'+datas[i].name+' ( '+datas[i].employee_code+' )</option>');
                    }
                    spinner.hide();
                }
            });
        }
        function get_user_id(id){
            var spinner = $('#loader');
            spinner.show();
            $('#leave_type').empty();
            var office_id = $('#office_id').val();
            var department_id = $('#department_id').val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-leave-type')}}",
                data: {
                    office_id: office_id,
                    department_id: department_id,
                    user_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#leave_type').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#leave_type').append('<option value="'+datas[i].id+'">'+datas[i].name+' ( '+datas[i].totalleave+' )</option>');
                    }
                    spinner.hide();
                }
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script>
        $(document).on('click','.status_checks',function(){
            var status = ($(this).hasClass("btn-success")) ? 'Approved' : 'Reject';
            var msg = (status=='Approved')? 'Approved' : 'Reject';
            var current_element = $(this);
            swal({
                title: "Are you sure?",
                text: "Do you want to change status "+msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var spinner = $('#loader');
                    spinner.show();
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        url: "{{url('employee-leave-status')}}",
                        type: "POST",
                        data: {id:$(current_element).attr('data'),status:status},
                        success: function(xhr){
                            if(xhr.data.status=='Reject'){
                                $('.inds'+xhr.data.id).addClass('btn-danger');
                                $('.inds'+xhr.data.id).removeClass('btn-success');
                                $('.inds'+xhr.data.id).text('Rejected');
                            }else{
                                $('.inds'+xhr.data.id).addClass('btn-success');
                                $('.inds'+xhr.data.id).removeClass('btn-danger');
                                $('.inds'+xhr.data.id).text('Approved');
                            }
                            $('.hides').hide();
                            spinner.hide();
                            swal(xhr.data.status, "Succesfully "+xhr.data.status, "success");
                            
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            spinner.hide();
                            swal("Error deleting!", "Please try again", "error");
                        }
                    });
                }
            });
        });
    </script>
@endsection('content')