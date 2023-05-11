@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Leave</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('leave-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
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
                            <div class="col-sm-4">
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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Type of Employee *</label>
                                    <select class="form-control" id="emp_type" name="emp_type" required>
                                        @if(!empty($employee_type))
                                            <option value="">--Select--</option>
                                            @foreach($employee_type as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->emp_type)) @if($update->emp_type==$row1->id) selected @endif @endif>{{$row1->emp_type}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Leave Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="@if(!empty($update->name)){{$update->name}}@endif" placeholder="Enter Leave Name" maxlength="70" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Total Number of Leave *</label>
                                    <input type="number" class="form-control" id="total_leave" name="total_leave" value="@if(!empty($update->total_leave)){{$update->total_leave}}@endif" placeholder="Enter Total Number Of Leave" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
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
                                <h5 class="" id="getCameraSerialNumbers">Leave List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Type of Employee</th>
                                    <th>Leave Name</th>
                                    <th>Total Number of Leave</th>
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
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-leave-masters')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'office_name'},
                {data:'department_name'},
                {data:'emp_type'},
                {data:'leaveName'},
                {data:'total_leave'},
                
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("leave-master")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("leave-master-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
    $(function () {
        $("#name").keypress(function (e) {
            if(e.which === 32) 
                return true;
            var keyCode = e.keyCode;
            $("#leaveNameError").html("");
            var regex = /^[A-Za-z]+$/;
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#leaveNameError").html("Only Alphabets allowed.");
            }
            return isValid;
        });
    });
</script>
@endsection('content')