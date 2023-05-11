@extends('layouts.user.app')
@section('content')
<style>
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    .tbl-border thead tr th{
        color: black;
        font-weight: 600;
        line-height:0;
    }
    #assets_data td{
        border: 1px solid #80808036 !important;
    }
    #assets_data2 td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    #assets_header{
        background: #63b3f7;
        padding: 2px 7px;
        color: white;
        border-radius: 9px;
    }
    </style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Request</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('assets-request')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Assets Type *</label>
                                    <select name="assets_type" id="assets_type" class="form-control">
                                        @if(!empty($assets_name))
                                            <option value="">--Select--</option>
                                            @foreach($assets_name as $row)
                                                <option value="{{$row->id}}" >{{$row->assets_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>End Date *</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea rows="2" name="description" class="form-control" required=""></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm">Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Request List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Assets Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
                <h4 class="modal-title">Assets Request for <span id="assets_header"></span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Email</th>
                                <th scope="col">Mobile Number</th>
                                <th scope="col">Assets Type</th>
                                <th scope="col">Start date</th>
                                <th scope="col">End date</th>
                                <th scope="col">Description</th>
                                <th scope="col">status</th>
                            </tr>
                        </thead>
                        <tbody id="assets_data">
                        </tbody>
                    </table>
                    <br/>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Admin Description</th>
                                <th scope="col">From Date</th>
                                <th scope="col">To Date</th>
                            </tr>
                        </thead>
                        <tbody id="assets_data2">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="assets_id">
                </div>
            </div>
        </div>
    </div>
    <script>
        function show_data(id){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/view-assets-data')}}", 
                data: {id:id},
                async : false,
                success:function(xhr){
                    if(xhr.status==200){
                        var html='<tr>'+
                        '<td>'+xhr.data.email+'</td>'+
                        '<td>'+xhr.data.mobile+'</td>'+
                        '<td>'+xhr.data.assets_name+'</td>'+
                        '<td>'+xhr.data.start_date+'</td>'+
                        '<td>'+xhr.data.end_date+'</td>'+
                        '<td>'+xhr.data.description+'</td>'+
                        '<td>'+xhr.data.status+'</td>'+
                        '</tr>';
                        var html2='<tr>'+
                            '<td>'+xhr.data.description_admin+'</td>'+
                            '<td>'+xhr.data.start_date_admin+'</td>'+
                            '<td>'+xhr.data.end_date_admin+'</td>'+
                        '</tr>';
                        $('#assets_header').text(xhr.data.name+' - '+xhr.data.employee_code);
                        $('#assets_data').html(html);
                        $('#assets_data2').html(html2);
                        $('#assets_id').html('<span class="btn btn-info btn-sm" data-dismiss="modal">Close</span>');
                        spinner.hide();
                    }
                }
            });
        }
    </script>
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
            ajax: "{{url('ajax/user-assets-request-list')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'assets_name'},
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
                {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.status=='Approve'){
                            return '<button class="status_checks btn-xs btn btn-outline-success ">'+data.status+'</button>';
                        }else{
                            return '<button class="status_checks btn-xs btn btn-outline-danger ">'+data.status+'</button>';
                        }
                    }
                },
                {data: null,
                    mRender:function (data, type, row ) {
                        return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="text-primary mx-2"><i class="fa fa-eye"></i></a>';
                    }
                },
            ]
        });
    });
</script>


    
@endsection('content')