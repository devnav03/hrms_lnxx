@extends('layouts.organization.app')
@section('content')
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
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Department</h5>
                    </div>
                    <div class="card-body">
                            @if(empty(Request::segment(2)))
                                <form class="forms-sample row" action="{{url('save-department-master')}}" method="POST">
                            @else
                                <form class="forms-sample row" action="{{url('update-department-master')}}" method="POST">
                            @endif
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required>
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->address}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department Name *</label>
                                    <input type="text" class="form-control" id="department_name" name="department_name" value="@if(!empty($update->department_name)){{$update->department_name}}@endif" placeholder="Enter Department Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Type Of Department</label>
                                    <select class="form-control" name="type_of_department" id="selectBox" onchange="changeFunc();">
                                        <option value="">Parent Department</option>
                                        <option value="department_id" @if(@$update->type_of_department==1) selected @endif>Sub Department</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" id="textboxes" @if(@$update->type_of_department==0) style="display:none" @endif>
                                <div class="form-group">
                                    <label>Parent Department</label>
                                    <select class="form-control" name="parent_id" onchange="changeFunc();" id="parent_id">
                                        @if(!empty($departments))
                                            <option value="" disabled>Select Parent Department</option>
                                            @foreach($departments as $row1)
                                                <option value="{{$row1->id}}" @if(!empty($update->parent_id)) @if($update->parent_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3" style="display: none" id="textboxesaddress">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" id="address" name="address" maxlength="200" rows="3" readonly required>@if(!empty($department->address)){{$department->address}}@endif</textarea>
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
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Department List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Type Of Department</th>
                                    <th>Parent Department</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if(!empty($results))
                                    @foreach($results as $rows)
                                    <?php $var = App\Models\DepartmentMaster::select('department_name')->where('parent_id',$rows->id)->first();
                                    ?>
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->office_name}}</td>
                                            <td>{{$rows->department_name}}</td>
                                            <td>@if($rows->type_of_department==1) Sub Department @else Parent Department @endif</td>
                                            <td>@if(!empty($rows->sub_department)){{$rows->sub_department}}@else -- @endif</td>
                                            <td>
                                                @if($rows->status=='Active')
                                                    <i data="{{$rows->id}}" id="inds{{$rows->id}}" class="status_checks btn-xs btn btn-outline-success">Active</i>
                                                @else
                                                    <i data="{{$rows->id}}" id="inds{{$rows->id}}" class="status_checks btn-xs btn btn-outline-danger">Inactive</i> 
                                                @endif
                                            </td>
                                            <td>{{date('d-M-Y', strtotime($rows->created_at))}}</td>
                                            <td>{{date('d-M-Y', strtotime($rows->updated_at))}}</td>
                                            <td>
                                                <!-- <a href data-toggle="modal" data-target="#myModal" onclick="show_data({{$rows->id}})" class="text-primary"><i class="fa fa-eye"></i></a> -->
                                                <a href="{{url("department-master",$rows->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                                <a href="{{url("department-master-delete",$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
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
                <h4 class="modal-title">Department</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Office</th>
                                <th scope="col">Department</th>
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
                url : "{{url('ajax/view-department-data')}}", 
                data: {id:id},
                async : false,
                success:function(xhr){
                    if(xhr.status==200){
                        var html='<tr>'+
                        '<td>'+xhr.data.office_name+'</td>'+
                        '<td>'+xhr.data.department_name+'</td>'+
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

    <script type="text/javascript">
        function changeFunc() {
            var selectBox = document.getElementById("selectBox");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "department_id") {
                $('#textboxes').show();
            } else {
                $('#textboxes').hide();
            }
        }
        $('#office_id').change(function(){
            $('#parent_id').empty();
            var office_id = $('#office_id').val();
            $('#textboxesaddress').show();
            $('#textboxesaddress').html($(this).find(':selected').data('id'));
            $.get("{{url('ajax/get-parent-department')}}/"+office_id+"",function(xhr){
                var datas = xhr.data;
                for (var i = 0; i < datas.length; i++) {
                    $('#parent_id').append('<option value="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
            });
        });

    </script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!--<script>-->
<!--    $(document).ready(function () {-->
<!--        var datatable = $('#example').dataTable({-->
<!--            ajax: "{{url('ajax/get-department-masters')}}",-->
<!--            columns: [-->
<!--                {data:'id',-->
<!--                    render: function (data, type, row, meta) {-->
<!--                        return meta.row + 1;-->
<!--                    }-->
<!--                },-->
<!--                {data:'office_name'},-->
<!--                {data:'department_name'},-->
<!--                {data:'sub_department'},-->
<!--                {data: null,-->
<!--                    mRender:function ( data, type, row ) {-->
<!--                        if(data.status=='Active'){-->
<!--                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-success">Active</i></td>';-->
<!--                        }else{-->
<!--                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-danger">Inactive</i></td>';-->
<!--                        }-->
<!--                    }-->
<!--                },-->
<!--                {data: null,-->
<!--                    mRender:function ( data, type, row ) {-->
<!--                        return dateTimeFormate(data.created_at);-->
<!--                    }-->
<!--                },-->
<!--                {data: null,-->
<!--                    mRender:function ( data, type, row ) {-->
<!--                        return dateTimeFormate(data.updated_at);-->
<!--                    }-->
<!--                },-->
<!--                {data: null,-->
<!--                    mRender:function ( data, type, row ) {-->
<!--                        return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="text-primary"><i class="fa fa-eye"></i></a>'+'<a href="{{url("department-master")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+-->
<!--                        '<a href="{{url("department-master-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';-->
<!--                    }-->
<!--                },-->
<!--            ]-->
<!--        });-->
<!--    });-->
<!--</script>-->
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
	dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                    url: "{{url('ajax/get-status-department')}}",
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
</script>
@endsection('content')