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
                        <form action="{{url('add-leave')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Office</label>
                                        <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                        <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id(this.value);">
                                            @if(!empty($office))
                                                <option value="">--Select--</option>
                                                @foreach($office as $row)
                                                    <option value="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Department Name</label>
                                        <select class="form-control" id="department_id" name="department_id" onchange="get_department_id(this.value);" required>
                                            <option value="">--Select--</option>
                                            @if(!empty($department))
                                                @foreach($department as $depa)
                                                    <option value="{{$depa->id}}" @if(!empty($update->department_id)) @if($update->department_id==$depa->id) selected @endif @endif>{{$depa->department_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="letterNameError" style="color:red;font-size:13px"></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Select Employee Name</label>
                                        <select class="form-control" id="user_id" onchange="get_user_id(this.value);"  name="user_id" required>
                                            <option value="">--Select--</option>
                                            @if(!empty($users))
                                                @foreach($users as $row)
                                                    <option value="{{$row->id}}" @if(!empty($update->user_id)) @if($update->user_id==$row->id) selected @endif @endif>{{$row->name}} ( {{$row->employee_code}} )</option>
                                                @endforeach
                                            @endif
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Type Of Leave</label>
                                        <select class="form-control" id="leave_type" name="leave_type" required>
                                            <option value="">--Select--</option>
                                            @if(!empty($leave_name))
                                                @foreach($leave_name as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo !empty($update->start_date) ? $update->start_date:'';?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo !empty($update->end_date) ? $update->end_date:'';?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Duration Day's</label>
                                        <input type="text" class="form-control" id="duration" name="duration" value="<?php echo !empty($update->duration) ? $update->duration:'';?>" placeholder="Duration Day's" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Reason for Leave</label>
                                        <textarea type="textarea" class="form-control" maxlength="300" id="reason_for_leav_comp" name="reason_for_leav_comp" required placeholder="Enter Your Reason Here"><?php echo !empty($update->reason_for_leav_comp) ? $update->reason_for_leav_comp:'';?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm">Add Leave</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <script>
        function get_office_id(id) {
            var spinner = $('#loader');
            spinner.show();
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
                    spinner.hide();
                }
            });
        }
        function get_department_id(id) {
            var spinner = $('#loader');
            spinner.show();
            $('#user_id').empty();
            $('#leave_type').empty();
            var office_id = $('#office_id').val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-employee-against-department')}}",
                data: {
                    office_id: office_id,
                    department_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#user_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#user_id').append('<option value="'+datas[i].id+'">'+datas[i].name+' ( '+datas[i].employee_code+' )</option>');
                    }
                    spinner.hide();
                }
            });
        }
        function get_user_id(id){
            var spinner = $('#loader');
            spinner.show();
            $('#leave_type').empty();
            var office_id = $('#office_id').val();
            var department_id = $('#department_id').val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-leave-type')}}",
                data: {
                    office_id: office_id,
                    department_id: department_id,
                    user_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#leave_type').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#leave_type').append('<option value="'+datas[i].id+'">'+datas[i].name+' ( '+datas[i].totalleave+' )</option>');
                    }
                    spinner.hide();
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