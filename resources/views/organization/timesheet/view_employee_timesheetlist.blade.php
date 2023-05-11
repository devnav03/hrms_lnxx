@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Search Employee Timesheet</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control" id="emp_id" name="emp_id" required>
                                        <option value="">Select</option>
                                            @if(!empty($timesheet_details))
                                                @foreach($timesheet_details as $row)
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
                                <h5 class="" id="getCameraSerialNumbers">Employee Timesheet List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration</th>
                                    <th>Project Name</th>
                                    <th>Activity Name</th>
                                    <th>Created At</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Timesheet</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body" id="description_timesheet">
                    
                </div>
                <div class="modal-footer">
                    <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
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
                    url: "{{url('ajax/view-emp-timesheet')}}",
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
                    {data:'start_time'},
                    {data:'end_time'},
                    {data:'duration'},
                    {data:'project_name'},
                    {data:'activity_name'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateTimeFormate(data.created_at);
                        }
                    },{data: null,
                        mRender:function ( data, type, row ) {
                            return '<a data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="text-primary mx-2"><i class="fa fa-eye"></i></a>';
                        }
                    },
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
                url : "{{url('ajax/get-timesheet-data')}}", 
                data: {id:id},
                async : false,
                success:function(xhr){
                    if(xhr.status==200){
                        $('#description_timesheet').html(xhr.data.description);
                        spinner.hide();
                    }
                }
            });

        }
    </script>
@endsection('content')