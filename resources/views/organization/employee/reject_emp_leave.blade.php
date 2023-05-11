@extends('layouts.organization.app')
@section('content')
<style>
    .lable-danger{
        background-color: #d9534f;
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
                            <h5 class="">Search Employee Reject Leave</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="row">
                                @csrf
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Employee Name</label>
                                        <select class="form-control" id="emp_id" name="emp_id" required>
                                            <option value="">Select</option>
                                                @if(!empty($emp_name))
                                                    @foreach($emp_name as $row)
                                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Month</label>
                                        <select class="form-control" id="month" name="month" required>
                                                <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select class="form-control" id="year" name="year" required>
                                            <option value="">Select</option>
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
                                        <th>Emp Name</th>
                                        <th>Applied On</th>
                                        <th>Duration</th>
                                        <th>Leave Type</th>
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
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Approved Leave</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                    <div class="modal-body">
                        <div id="leave_reason"></div>
                        <table class="table tbl-border">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Applied on</th>
                                    <th scope="col">Leave Type</th>
                                    <th scope="col">Start Data</th>
                                    <th scope="col">End Data</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Current Status</th>
                                </tr>
                            </thead>
                            <tbody id="leave_data">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" id="leave_id">
                    </div>
                </div>
            </div>
        </div>

    
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

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
                    url: "{{url('ajax/get-emp-reject-leave-data')}}",
                    dataType:"json",
                    type: 'POST',
                    data: $('form').serialize(),
                        success: function(data) {
                            callback(data);
                        }
                    });
                },
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'name'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.created_at);
                        }
                    },
                    // {data:'start_date'},
                    // {data:'end_date'},
                    {data:'duration'},
                    {data:'leave_type'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a class="lable-danger">'+data.status+'</a>';
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="lable-success"><i class="fa fa-eye"> View</i></a>';
                        }
                    }
                ]
            });
            spinner.hide();
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
                async : false,
                success:function(xhr){
                    if(xhr.status==200){
                        var html='<tr>'+
                        '<td>'+xhr.data.name+'</td>'+
                        '<td>'+xhr.data.mobile+'</td>'+
                        '<td>'+dateFormate(xhr.data.created_at)+'</td>'+
                        '<td>'+xhr.data.leave_type+'</td>'+
                        '<td>'+dateFormate(xhr.data.start_date)+'</td>'+
                        '<td>'+dateFormate(xhr.data.end_date)+'</td>'+
                        '<td>'+xhr.data.duration+' Days</td>'+
                        '<td>'+xhr.data.reason_for_leav_comp+'</td>'+
                        '<td><a class="lable-danger">'+xhr.data.status+'</a>'+
                        '</td></tr>';
                        $('#leave_data').html(html);
                        $('#leave_id').html('<a href="{{url('employee-leave-status')}}/'+id+'/1" class="btn btn-success btn-sm">Approve</a>'+
                            '<a href="{{url('employee-leave-status')}}/'+id+'/2" class="btn btn-danger btn-sm">Reject</a>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        spinner.hide();
                    }
                }
            });
        }
    </script>
    
@endsection('content')