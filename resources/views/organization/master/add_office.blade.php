@extends('layouts.organization.app')
@section('content')
<style>
    .select2-container .select2-selection--single {
        height: 2.2rem !important;
    }
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaaaaa73 !important;
        border-radius: 0px !important;
    }
    .select2-container{
        width:100% !important;
    }
</style>
<style>
    .lable-primary{
        background-color: #337ab7;
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Office</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('office-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Office Name *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="text" class="form-control" id="office_name" name="office_name" value="@if(!empty($update->office_name)){{$update->office_name}}@endif" placeholder="Enter Office Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Country *</label>
                                    <select class="form-control country_name" id="country_id" name="country_id" onchange="get_country_id(this.value);" required>
                                        @if(!empty($country))
                                            <option value="">--Select--</option>
                                            @foreach($country as $country)
                                                <option value="{{$country->id}}" @if(!empty($update->country_id)) @if($update->country_id==$country->id) selected @endif @endif>{{$country->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>State *</label>
                                    <select id="state_id" name="state_id" onchange="get_state_id(this.value);"
                                        class="form-control state_name" required>
                                        @if(!empty($state))
                                            <option value="">--Select--</option>
                                            @foreach($state as $rows)
                                                <option value="{{$rows->id}}" @if(!empty($update->state_id)) @if($update->state_id==$rows->id) selected @endif @endif>{{$rows->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>City *</label>
                                    <select id="city_id" name="city_id" class="form-control city_name" required>
                                        @if(!empty($city))
                                            <option value="">--Select--</option>
                                            @foreach($city as $rows1)
                                                <option value="{{$rows1->id}}" @if(!empty($update->city_id)) @if($update->city_id==$rows1->id) selected @endif @endif>{{$rows1->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Pincode *</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" value="@if(!empty($update->pincode)){{$update->pincode}}@endif"
                                        placeholder="Enter Pincode" pattern="[0-9]*" maxlength="6" minlength="6"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div id="map">
                                        <button class="btn btn-secondary btn-sm mr-2" style="margin-top:1.8rem" disabled>Search Geolocation</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address *</label>
                                    <textarea class="form-control" id="address" name="address" maxlength="200" rows="3" placeholder="Enter Address" required>@if(!empty($update->address)){{$update->address}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                            @if(!empty($update))
                            <div class="col-sm-1">
                                <a href="{{url('office-master')}}" class="btn btn-primary btn-sm">Back</a>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Office List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
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
                <h4 class="modal-title">Office</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Office</th>
                                <th scope="col">Country</th>
                                <th scope="col">State</th>
                                <th scope="col">City</th>
                                <th scope="col">Pincode</th>
                                <th scope="col">address</th>
                                <th scope="col">Status</th>
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

    <script>
        function show_data(id){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/view-office-data')}}", 
                data: {id:id},
                async : false,
                success:function(xhr){
                    if(xhr.status==200){
                        var html='<tr>'+
                        '<td>'+xhr.data.office_name+'</td>'+
                        '<td>'+xhr.data.countryName+'</td>'+
                        '<td>'+xhr.data.stateName+'</td>'+
                        '<td>'+xhr.data.cityName+'</td>'+
                        '<td>'+xhr.data.pincode+'</td>'+
                        '<td>'+xhr.data.address+'</td>'+
                        '<td><a class="lable-primary">'+xhr.data.status+'</a>'+
                        '</td></tr>';
                        $('#leave_data').html(html);
                        $('#leave_id').html('<span class="btn btn-info btn-sm" data-dismiss="modal">Close</span>');
                        spinner.hide();
                    }
                }
            });
        }
    </script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-office-masters')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'office_name'},
                {data:'state'},
                {data:'city'},

                <!--{data:'status'},-->

                {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.status=='Active'){
                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-success">Active</i></td>';
                        }else{
                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-danger">Inactive</i></td>';
                        }
                    }
                },

                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.created_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.updated_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="text-primary"><i class="fa fa-eye"></i></a>'+'<a href="{{url("office-master")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("office-master-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
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
                    url: "{{url('ajax/get-status-office')}}",
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

<script>
    function get_country_id(id) {
        $('#state_id').empty();
        $('#city_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-state')}}",
            data: {
                country_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#state_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#state_id').append('<option value="' + datas[i].id + '">' + datas[i].name +
                        '</option>');
                }
            }
        });
    }
</script>

<script>
    function get_state_id(id) {
        $('#city_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-city')}}",
            data: {
                state_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#city_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#city_id').append('<option value="' + datas[i].id + '">' + datas[i].name +
                        '</option>');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    $(function () {
        $("#source_name").keypress(function (e) {
            if(e.which === 32) 
                return true;
            var keyCode = e.keyCode;
            $("#letterNameError").html("");
            var regex = /^[A-Za-z]+$/;
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#letterNameError").html("Only Alphabets allowed.");
            }
            return isValid;
        });
    });

    $(document).ready(function() {
        $('.country_name').select2();
        $('.state_name').select2();
        $('.city_name').select2();
    });
</script>
@endsection('content')