@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Search Employee Attendance</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control" id="emp_id" name="emp_id" required>
                                        <option value="">Select</option>
                                            @if(!empty($employee_atten_details))
                                                @foreach($employee_atten_details as $row)
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
                                <h5 class="" id="getCameraSerialNumbers">Employee Attendance List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Code</th>
                                    <th>Name</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Total Working Hr</th>
                                    <th>In Image</th>
                                    <th>Out Image</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
                    url: "{{url('ajax/get-employee-attendance-details')}}",
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
                    {data:'employee_code'},
                    {data:'name'},
                    {data:'in_time'},
                    {data:'out_time'},
                    {data:'total_time'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            if(data.in_status == 'Yes'){
                             return '<a href="https://ams.facer.in/'+data.in_image+'" target="_blank"><img src="https://ams.facer.in/'+data.in_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';

                            } else {

                            if(data.in_image != null){
                            return '<a href="{{asset("employee/attendance")}}/'+data.in_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.in_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';
                           } else {
                              return 'N/A';
                           }
                        }

                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {

                            if(data.out_status == 'Yes'){
                             return '<a href="https://ams.facer.in/'+data.out_image+'" target="_blank"><img src="https://ams.facer.in/'+data.out_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';

                            } else {

                            if(data.out_image != null){
                                return '<a href="{{asset("employee/attendance")}}/'+data.out_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.out_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';
                            } else {
                                return 'N/A';
                            }

                           }
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.created_at);
                        }
                    },
                ]
            });
            spinner.hide();
        });
      });
    </script>
    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/get-employee-attendance-details')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'employee_code'},
                    {data:'name'},
                    {data:'in_time'},
                    {data:'out_time'},
                    {data:'total_time'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            if(data.in_status == 'Yes'){
                             return '<a href="https://ams.facer.in/'+data.in_image+'" target="_blank"><img src="https://ams.facer.in/'+data.in_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';

                            } else {
                             if(data.in_image != null){
                            return '<a href="{{asset("employee/attendance")}}/'+data.in_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.in_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';
                        } else {
                              return 'N/A';
                        }
                        }
                    }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                             if(data.out_status == 'Yes'){
                             return '<a href="https://ams.facer.in/'+data.out_image+'" target="_blank"><img src="https://ams.facer.in/'+data.out_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';

                            } else {
                             if(data.out_image != null){
                                return '<a href="{{asset("employee/attendance")}}/'+data.out_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.out_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';
                            } else {
                                return 'N/A';
                            }
                        }
                    }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.created_at);
                        }
                    },
                ]
            });
        });
    </script>
@endsection('content')