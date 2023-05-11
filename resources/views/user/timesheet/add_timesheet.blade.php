@extends('layouts.user.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Timesheet</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('add-timesheet')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <select class="form-control" id="project_id" name="project_id" onchange="get_activity_id(this.value);" required>
                                            <option value="">Select</option>
                                            @if(!empty($project)) @foreach($project as $rows)
                                            <option value="{{$rows->id}}">{{$rows->project_name}}</option>
                                            @endforeach @endif 
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Activity</label>
                                        <select class="form-control" id="activity_id" name="activity_id" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>HM:MM</label>
                                        <input type="text" class="form-control" id="duration" name="duration" placeholder="HM:MM" readonly required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option>Select</option>
                                            <option value="1">Pending</option>
                                            <option value="2">In Process</option>
                                            <option value="3">Complete</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea type="hiden" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace( 'description' );
        function get_activity_id(id){
        $('#activity_id').empty();
        $.ajax({  
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            url : "{{url('ajax/get-activity')}}", 
            data: {project_id:id},
            success: function(xhr){  
                var datas =  xhr.data;
                for(var i=0;i<datas.length;i++){
                    $('#activity_id').append('<option value="'+datas[i].id+'">'+datas[i].activity_name+'</option>');
                }
            }
        });
    }
    </script>
    <script type="text/javascript">
        var att = '<?php echo  !empty($emp_attendances[0]->MinuteDiff) ? $emp_attendances[0]->MinuteDiff:0;?>';
        console.log(att);
        $(document).ready(function() {    
        function calculateTime() {
            var valuestart = $("#start_time").val();
            var valuestop = $("#end_time").val();
            //create date format          
            var timeStart = new Date("{{date('Y-m-d')}} "+valuestart).getTime();
            var timeEnd = new Date("{{date('Y-m-d')}} "+valuestop).getTime();
            var hourDiff = timeEnd - timeStart;  
            var mins = Math.floor(hourDiff / 60000);
            var hrs = Math.floor(mins / 60);
            var days = Math.floor(hrs / 24);
            var yrs = Math.floor(days / 365);
            mins = mins % 60;
            $("#duration").val(hrs +":"+ mins )  
            hrs = hrs % 24;
            sec = hrs * 60 * 60;
            // checkduration(sec);
            console.log(sec);
            if(sec > att){
                toastr.error("Your attendance hours is not matching timesheet hours");
                $("#duration").val('00:00');  
            }
    }
        $("#start_time, #end_time").change(calculateTime);
            calculateTime();
        });  
        function show1(elem){
            if(elem.value == '1') document.getElementById('div1').style.display = "block";
            if(elem.value == '0') document.getElementById('div1').style.display = "none";
        }
        function show2(elem){
            if(elem.value == '1') document.getElementById('div2').style.display = "block";
            if(elem.value == '0') document.getElementById('div2').style.display = "none";
        }
        $(document).ready(function() {
            $('#start_time').timepicker( {
                showAnim: 'blind'
            } );
        });
        $(document).ready(function() {
            $('#end_time').timepicker( {
                showAnim: 'blind'
            } );
        });
    </script>
    @endsection('content')