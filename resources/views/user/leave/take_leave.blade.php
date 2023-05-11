@extends('layouts.user.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class=""> Leave request</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('take-leave')}}" method="POST">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" min="{{date('Y-m-d')}}" placeholder="Start Date" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="Duration" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Leave Type</label>
                                    <select class="form-control" id="leave_type" name="leave_type" required>
                                        <option value="">Select Leave Type</option>
                                        @if(!empty($leave_type))
                                            @foreach($leave_type as $row)
                                                <option value="{{$row->id}}">{{$row->name}} ( {{$row->totalleave}} )</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <textarea type="hiden" name="reason_for_leav_comp"></textarea>
                                    <script>CKEDITOR.replace('reason_for_leav_comp');</script>
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