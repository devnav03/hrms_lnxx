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
                        <div class="card-header card-height">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <h5 class="" id="getCameraSerialNumbers">Leave List</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Emp Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Type Of Leave</th>
                                        <th>Leave Reason</th>
                                        <th>Leave Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- <div id="myModal" class="modal fade" role="dialog">
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
        </div> -->

    
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/get-list-leave')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'name'},
                    {data:'start_date'},
                    {data:'end_date'},
                    {data:'leave_type'},
                    {data:'reason_for_leav_comp'},
                    {data:'status'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a href="{{url("list-leave-status")}}/'+data.id+'/1" class="lable-success mx-1">Approve</a>'+'<a href="{{url("list-leave-status")}}/'+data.id+'/2" class="lable-danger mx-1">Reject</a>'+'<a href="{{url("edit-leave")}}/'+data.id+'" class="text-primary mx-1"><i class="fa fa-edit"></i></a>'+
                            '<a href="{{url("delete-list-leave")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                        }
                    },
                ]
            });
        });
    </script>


    
@endsection('content')