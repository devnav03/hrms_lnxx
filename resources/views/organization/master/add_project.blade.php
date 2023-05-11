@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Task Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-project')}}" method="POST">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Task Type *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="task_master" name="task_master" required>
                                        <option value="">--Select--</option>
                                        <option value="Simple Task" @if(!empty($update->task_master)) @if($update->task_master=="Simple Task") selected @endif @endif>Simple Task</option>
                                        <option value="Moderate Task" @if(!empty($update->task_master)) @if($update->task_master=="Moderate Task") selected @endif @endif>Moderate Task</option>
                                        <option value="Complex Task" @if(!empty($update->task_master)) @if($update->task_master=="Complex Task") selected @endif @endif>Complex Task</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
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
                                    <select class="form-control" id="department_id" name="department_id">
                                        @if(!empty($department))
                                            <option value="">--Select--</option>
                                            @foreach($department as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Task Name *</label>
                                    <input type="text" class="form-control" id="project_name" name="project_name" value="@if(!empty($update->project_name)){{$update->project_name}}@endif" placeholder="Enter Task Name" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Task Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="@if(!empty($update->start_date)){{$update->start_date}}@endif" placeholder="Enter Task Start Date" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Task End Date *</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="@if(!empty($update->end_date)){{$update->end_date}}@endif" placeholder="Enter Task End Date" maxlength="50" required>
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
                                <h5 class="" id="getCameraSerialNumbers">Task List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Task Master</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Task Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Activity</th>
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
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title project_activities_header"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body" id="project_activities">
                    <form class="forms-sample row" id="form_activities" method="POST">
                        @csrf
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Activity Name</label>
                                <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                <input type="hidden" name="project_id" id="project_id" class="form-control" value="">
                                <input type="text" class="form-control" id="activity_name" name="activity_name" value="@if(!empty($update->activity_name)){{$update->activity_name}}@endif" placeholder="Enter Activity Name" maxlength="50" required>
                                <span id="letterNameError" style="color:red;font-size:13px"></span>
                            </div>
                        </div>
                        <div class="col-sm-4" style="margin-top: 1.9rem !important;">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="card-body">
                        <table id="activities" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Activities Name</th>
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
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#example').dataTable().fnClearTable();
        $('#example').dataTable().fnDraw();
        $('#example').dataTable().fnDestroy();
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-project-list')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'task_master'},
                {data:'office_name'},
                {data:'department_name'},
                {data:'project_name'},
                {data:'start_date'},
                {data:'end_date'},
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
                        return '<a data-toggle="modal" data-target="#myModal" data-name="'+data.project_name+'" id="dx'+data.id+'" onclick="show_data('+data.id+')" class="text-primary btn btn-outline-success btn-xs"><i class="fa fa-plus" style="font-size:12px"></i> Add</a>';
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("add-project")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("project-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ]
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
                    url: "{{url('ajax/get-status-project')}}",
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
    function show_data(id){
        $('#project_id').val(id);
        $('.project_activities_header').html($('#dx'+id).attr("data-name")+' Task Activities');
        $('#activities').dataTable().fnClearTable();
        $('#activities').dataTable().fnDraw();
        $('#activities').dataTable().fnDestroy();
        var datatable = $('#activities').dataTable({
            ajax: "{{url('ajax/get-activities-list')}}/"+id,
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'activity_name'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.created_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a onclick="delete_this('+data.id+')" class="text-danger"><i class="fa fa-trash"></i></a>';
                    }
                },
            ]
        });
    }
    function delete_this(id){
        if(confirm("Are you sure to delete this")){
            $.ajax({type: 'GET',url : "{{url('ajax/delete-project-activities')}}/"+id,
                success:function(xhr){
                    if(xhr.status==200){
                        toastr.success(xhr.message);
                        $('#activities').DataTable().ajax.reload()
                    }
                    spinner.hide();
                }
            });
        }
    }
</script>
<script>
    $(function () {
        $('#form_activities').on('submit', function (e) {
        var spinner = $('#loader');
        spinner.show();
            e.preventDefault();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/save-project-activities')}}", 
                data: $('form').serialize(),
                success:function(xhr){
                    if(xhr.status==200){
                        toastr.success(xhr.message);
                        $('#activity_name').val('');
                        $('#activities').DataTable().ajax.reload()
                    }else{
                        toastr.error(xhr.message);
                    }
                    spinner.hide();
                }
            });
        });
    });
</script>
<script>
        function get_office_id() {
            var department_id = $('#office_id option:selected').data('id');
            $('#department_id').empty();
            $('#designation_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-department-name')}}",
                data: {
                    department_id: department_id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }
                }
            });
        }
    </script>
    <script>
    $("#start_date").on("change", function(){
        $("#end_date").attr("min", $(this).val());
    });
    $("#end_date").on("change", function(){
        var start = $("#start_date").val();
        var end = $("#end_date").val();

        var startDay = new Date(start);
        var endDay = new Date(end);
        var millisecondsPerDay = 1000 * 60 * 60 * 24;

        var millisBetween = endDay.getTime() - startDay.getTime();
        var days = millisBetween / millisecondsPerDay;
        $("#duration").val(Math.floor(days));
    });
</script>
@endsection('content')