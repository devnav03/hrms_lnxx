@extends('layouts.superadmin.app')
@section('content')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                @if(!empty($update->company_name))
                <form action="{{route('organization-update')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="up_id" value="{{$update->id}}">    
                @else
                <form action="{{url('add-organization')}}" method="POST" enctype="multipart/form-data">
                @endif
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                        <div class="col-md-9 col-6">
                            <h5 class="">Organisation</h5>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="form-group">
                            <label class="switch">
                                <input type="checkbox" name="active_notification" checked>
                                <span class="slider round"></span>
                            </label>
                            <label>Send Notification</label>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="@if(!empty($update->company_name)){{$update->company_name}}@endif" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" class="form-control" style="text-transform: lowercase;" id="user_name" name="user_name" placeholder="User Name" maxlength="20" value="@if(!empty($update->user_name)){{$update->user_name}}@endif" @if(!empty($update->user_name))disabled @endif onkeyup="CheckUsername(this.value);" required>
                                    <div class="help-block checking" style="font-size: 12px;"></div>
                                </div>
                            </div>
                            @if(empty($update->user_name))
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            @endif
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" pattern="[0-9]*" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="@if(!empty($update->mobile)){{$update->mobile}}@endif" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="@if(!empty($update->email)){{$update->email}}@endif" onkeyup="CheckEmail(this.value);" required>
                                    <div class="help-block checking_email" style="font-size: 12px;"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Address" value="@if(!empty($update->address)){{$update->address}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option>Select Status</option>
                                        <option value="Active" @if(!empty($update->status)) if($update->status=='Active') selected @endif>Active</option>
                                        <option value="Inactive" @if(!empty($update->status)) if($update->status=='Inactive') selected @endif>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Logo Upload</label>
                                    <input type="file" name="logo" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Logo">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn-sm btn-primary" type="button">Upload</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success btn-sm mr-2 organisation_button">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Organisation</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Status</th>
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
    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/organisation-details')}}",
                columns: [
                    {data:'m_id'},
                    {data:'user_name'},
                    {data:'name'},
                    {data:'email'},
                    {data:'mobile'},
                    {data:'address'},
                    {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.status=='Active'){
                            return '<td><i data="'+data.m_id+'" id="inds'+data.m_id+'" class="status_checks btn-xs btn btn-outline-success">Active</i></td>';
                        }else{
                            return '<td><i data="'+data.m_id+'" id="inds'+data.m_id+'" class="status_checks btn-xs btn btn-outline-danger">Inactive</i></td>';
                        }
                    }
                },
                    {data:'created_at'},
                    {data: null,
                    orderable: false,
                        "mRender" : function ( data, type, row ) {
                            return '<a href="{{url("update-organization")}}/'+data.id+'" class="text-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>';
                            // '<a href="{{url("delete-organization")}}/'+data.m_id+'" class="text-danger"><i class="fa fa-trash"></i></a>';
                        }
                    },
                ]
            });
            // setInterval(function(){
            //     $('#example').DataTable().ajax.reload(); 
            // },3000);
        });
    </script>
    <script type="text/javascript">
        function CheckUsername(username) {
            $('.organisation_button').removeAttr("disabled");
            var check_container = $('.checking');
            var check_input = username;
            if(check_input == '') {
                check_container.empty();
                return false;
            }
            check_container.removeClass('text-danger').removeClass('text-primary').html('<span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
            if(username.length >= 4){
                $.ajax({  
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url : "{{url('ajax/check-username')}}", 
                    data: "user_name="+ check_input,
                    success: function(data){  
                        if(data.status == 200) {
                            check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('text-danger').addClass('text-primary');
                            $('.organisation_button').removeAttr("disabled");
                        } else if(data.status == 404) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('text-primary').addClass('text-danger');
                            $('.organisation_button').attr("disabled", true);
                        } else if(data.status == 401) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('text-primary').addClass('text-danger');
                            $('.organisation_button').attr("disabled", true);
                        }
                    }
                }); 
            } else if(username.length <= 3){
                setTimeout(function(){
                    check_container.html('<i class="fa fa-remove"></i> Too short.').removeClass('text-primary').addClass('text-danger');
                    $('.organisation_button').attr("disabled", true);
                },3000);
            }
        }
        function CheckEmail(email) {
            $('.organisation_button').removeAttr("disabled");
            var check_container = $('.checking_email');
            var check_input = email;
            if(check_input == '') {
                check_container.empty();
                return false;
            }
            check_container.removeClass('text-danger').removeClass('text-primary').html('<span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
            if(check_input.length >= 4){
                $.ajax({  
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url : "{{url('ajax/check-email')}}", 
                    data: "email="+ check_input,
                    success: function(data){  
                        if(data.status == 200) {
                            check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('text-danger').addClass('text-primary');
                            $('.organisation_button').removeAttr("disabled");
                        } else if(data.status == 404) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('text-primary').addClass('text-danger');
                            $('.organisation_button').attr("disabled", true);
                        } else if(data.status == 401) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('text-primary').addClass('text-danger');
                            $('.organisation_button').attr("disabled", true);
                        }
                    }
                }); 
            }
        }
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
    $(document).on('click','.status_checks',function(){
        var status = ($(this).hasClass("btn-outline-success")) ? 'Inactive' : 'Active';
        var msg = (status=='Active')? 'Active' : 'Inactive';
        var current_element = $(this);
        swal({
            title: "Are you sure?",
            text: "Do you want to change status "+msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change status!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/update-organization-status')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),status:status},
                    success: function(xhr){
                        if(xhr.data.status=='Inactive'){
                            $('#inds'+xhr.data.id).addClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-success');
                            $('#inds'+xhr.data.id).text('Inactive');
                            // toastr.success("Inactive");
                        }else{
                            $('#inds'+xhr.data.id).addClass('btn-outline-success');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).text('Active');
                            // toastr.success("Active");
                        }
                        swal(xhr.data.status, "Succesfully "+xhr.data.status, "success");
                        
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Something Went to Wrong!", "Please try again", "error");
                    }
                });
            }
        });
    });
</script>

    
    @endsection('content')