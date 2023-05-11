@extends('layouts.organization.app')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <h5 class="">Attendance Details</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Total Time</th>
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
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Mark Attendance</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="my_camera" style="width:100%"></div>
                            <input type=button class="btn btn-primary btn-sm btn-block" value="Take Snapshot" onClick="take_snapshot()">
                            <input type="hidden" name="snapshot" class="image-tag">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>
                        <div class="col-md-6">
                            <div id="results"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
                    <button class="btn btn-success btn-sm" id="show_button" style="display:none;">Submit</button>
                </div>
            </form>
            </div>
            
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/all-employee-attendances')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'in_time'},
                    {data:'out_time'},
                    {data:'total_time'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a href="{{asset("employee/attendance")}}/'+data.in_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.in_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a href="{{asset("employee/attendance")}}/'+data.out_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.out_image+'" style="width: 3.187rem;height: 3.187rem;"/></a>';
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            var dt = new Date(data.created_at);
                            return dt.getDate() + "-" +dt.getMonth()+ "-" + dt.getFullYear();
                        }
                    },
                ]
            });
        });
    </script>
    @endsection('content')