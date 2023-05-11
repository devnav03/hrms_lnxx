@extends('layouts.organization.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Employee</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row" action="{{url('add-emp-assign-project')}}">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control" id="employee_id" name="employee_id" required>
                                        <option value="">Select</option>
                                            @if(!empty($emp_details))
                                                @foreach($emp_details as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary btn-sm mr-2" style="margin-top: 1.9rem;">Submit</button>
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
                                <h5 class="" id="getCameraSerialNumbers">Employee List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Project Name</th>
                                    <th>Employee Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
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
                    url: "{{url('ajax/view-emp-assign-pro')}}",
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
                    {data:'project_name'},
                    {data:'name'},
                    {data:'start_date'},
                    {data:'end_date'},
                    {data:'description'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateTimeFormate(data.created_at);
                        }
                    }
                ]
            });
            spinner.hide();
        });
      });








        // $(document).ready(function () {
        //     var datatable = $('#example').dataTable({
        //         ajax: "{{url('ajax/view-emp-assign-pro')}}",
        //         columns: [
        //             {data:'id',
        //                 render: function (data, type, row, meta) {
        //                     return meta.row + 1;
        //                 }
        //             },
        //             {data:'project_name'},
        //             {data:'name'},
        //             {data:'start_date'},
        //             {data:'end_date'},
        //             {data:'description'},
        //             {data: null,
        //                 mRender:function ( data, type, row ) {
        //                     return dateTimeFormate(data.created_at);
        //                 }
        //             },
        //         ]
        //     });
        // });
    </script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>
@endsection('content')