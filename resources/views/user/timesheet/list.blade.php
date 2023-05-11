@extends('layouts.user.app')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-10 col-6">
                                <h5 class="">View Timesheet</h5>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
                <h4 class="modal-title">View Leave Reason</h4>
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
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/view-timesheet')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'start_time'},
                    {data:'end_time'},
                    {data:'duration'},
                    {data:'project_name'},
                    {data:'activity_name'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            var dt = new Date(data.created_at);
                            return dt.getDate() + "-" +dt.getMonth()+ "-" + dt.getFullYear()+' '+dt.getHours()+':'+dt.getMinutes();
                        }
                    },{data: null,
                        mRender:function ( data, type, row ) {
                            return '<a data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="text-primary mx-2"><i class="fa fa-eye"></i></a>';
                        }
                    },
                ]
            });
        });
        function show_data(id){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/get-timesheet-data')}}", 
                data: {id:id},
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