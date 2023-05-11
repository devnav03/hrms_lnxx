@extends('layouts.organization.app')
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

        <form action="{{url('assets-report')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-2 col-sm-12 col-xs-12 form-group">
                    <select name="show" class="form-control">
                        <option value="5" selected=""> All Status </option>
                        <option value="0"> Cancel </option>
                        <option value="1"> Pending </option>
                        <option value="2"> Rejected </option>
                        <option value="3"> Approved </option>
                        <option value="4"> Return </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm mr-2">Search</button>
                </div>
            </div>
        </form>
        
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Report</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Office Name</th>
                                <th>Name</th>
                                <th>Assets Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($result)) @foreach($result as $row)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$row->office_name}}</td>
                                <td>{{$row->name}} - {{$row->employee_code}}</td>
                                <td>{{$row->assets_name}}</td>
                                <td><button class="status_checks btn-xs btn 
                                @if($row->status=='Approve') btn-outline-success @else btn-outline-danger @endif">{{$row->status}}</button></td>
                                <td>{{date_format(date_create($row->created_at),"d-M-Y H:i")}}</td>
                                <td>
                                    <a href data-toggle="modal" data-target="#myModal" onclick="show_data({{$row->id}})" class="text-primary mx-2"><i class="fa fa-eye"></i></a>
                                    <a href="{{url('update-assets-status',$row->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                            @endforeach @endif
                        </tbody>
                    </div>
                </div>
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
            var datatable = $('#examples').dataTable();
        });
    </script>
@endsection('content')