@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Edit Leave</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('update-leave', $leave_details->id)}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Select Employee Name</label>
                                        <select class="form-control" id="user_id" name="user_id">
                                            <option value="">Select</option>
                                            @if(!empty($emp_name))
                                                @foreach($emp_name as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Type Of Leave</label>
                                        <select class="form-control" id="leave_type" name="leave_type">
                                            <option value="">Select</option>
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
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="@if(!empty($leave_details->start_date)){{$leave_details->start_date}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="@if(!empty($leave_details->end_date)){{$leave_details->end_date}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Duration</label>
                                        <input type="text" class="form-control" id="duration" name="duration" value="@if(!empty($leave_details->duration)){{$leave_details->duration}}@endif"  placeholder="Duration" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Reason of Leave</label>
                                        <textarea type="textarea" class="form-control" maxlength="300" id="reason_for_leav_comp" name="reason_for_leav_comp" required placeholder="Enter Your Reason Here">{{$leave_details->reason_for_leav_comp}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm">Save Leave</button>
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