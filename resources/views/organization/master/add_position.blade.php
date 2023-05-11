@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Designation</h5>
                    </div>
                    <div class="card-body">
                            @if(empty(Request::segment(2)))
                                <form class="forms-sample row" action="{{url('add-position')}}" method="POST">
                            @else
                                <form class="forms-sample row" action="{{url('update-position')}}" method="POST">
                            @endif
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id(this.value);">
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department *</label>
                                    <select class="form-control" id="department_id" name="department_id" required>
                                        @if(!empty($department))
                                            <option value="">--Select--</option>
                                            @foreach($department as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Designation Name *</label>
                                    <input type="text" class="form-control" id="position_name" name="position_name" value="@if(!empty($update->position_name)){{$update->position_name}}@endif" placeholder="Enter Designation Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Type Of Designation</label>
                                    <select class="form-control" name="type_of_position" id="selectBox" onchange="changeFunc();">
                                        <option value="">Parent Designation</option>
                                        <option value="position_id" @if(@$update->type_of_position==1) selected @endif>Sub Designation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" id="textboxes" @if(@$update->type_of_position==0) style="display:none" @endif>
                                <div class="form-group">
                                    <label>Parent Designation</label>
                                    <select class="form-control" name="parent_id" onchange="changeFunc();" id="parent_id">
                                        @if(!empty($position_master))
                                            <option value="" disabled>--Select--</option>
                                            @foreach($position_master as $row1)
                                                <option value="{{$row1->id}}" @if(!empty($update->parent_id)) @if($update->parent_id==$row1->id) selected @endif @endif>{{$row1->position_name}}</option>
                                            @endforeach
                                        @endif
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
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Designation List</h5>
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
                                    <th>Designation</th>
                                    <th>Type Of Designation</th>
                                    <th>Parent Designation</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if(!empty($results))
                                    @foreach($results as $rows)

                                    <?php $var = App\Models\PositionMaster::select('position_name', 'department_id')->where('parent_id',$rows->id)->first();
         

                                    ?>
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->office_name}}</td>
                                            <td>{{ $rows->department_name }}</td>
                                            <td>{{$rows->position_name}}</td>
                                            <td>@if($rows->type_of_position==1) Sub Designation @else Parent Designation @endif</td>
                                            <td>@if(!empty($rows->sub_position)){{$rows->sub_position}}@else -- @endif</td>
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
                                                <a href="{{url("add-position",$rows->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                                <a href="{{url("position-delete",$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
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
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are You Sure Want to Delete?</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-footer">
                <span id="delte_id"></span>
                <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
            </div>
            </div>
            
        </div>
    </div>
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
	  dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
	});
    });
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!-- <script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-position-masters')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'office_name'},
                {data:'department_name'},
                {data:'position_name'},
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
                        return dateTimeFormate(data.created_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateTimeFormate(data.updated_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("add-position")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("position-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
</script> -->
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
                    url: "{{url('ajax/get-status-position')}}",
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
                        swal("Error deleting!", "Please try again", "error");
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
<script>
    function get_office_id(id) {
        var department_id = $('#office_id option:selected').data('id');
        $('#department_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-department-name')}}",
            data: {
                department_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#department_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#department_id').append('<option value="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
            }
        });
    }
</script>
<script type="text/javascript">
    function changeFunc() {
        var selectBox = document.getElementById("selectBox");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        if (selectedValue == "position_id") {
            $('#textboxes').show();
        } else {
            $('#textboxes').hide();
        }
    }
    $('#department_id').change(function(){
        var office_id = $('#office_id').val();
        if(office_id!=null){
            $('#parent_id').empty();
            var department_id = $('#department_id').val();
            $.get("{{url('ajax/get-parent-position')}}/"+office_id+"/"+department_id+"",function(xhr){
                var datas = xhr.data;
                $('#parent_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#parent_id').append('<option value="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                }
            });
        }else{
            toastr.error("Please select office first");
        }
    });
</script>
@endsection('content')