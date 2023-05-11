@extends('layouts.organization.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Schedule Interview ➤ {{$result->salutation}} {{$result->first_name}} {{$result->middle_name}} {{$result->last_name}}</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('save-interview')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Meeting Type</label>
                                    <input type="hidden" value="{{$result->id}}" name="candidate_id" class="form-control" required>
                                    <select name="meeting_type" class="form-control" required>
                                        <option value="" selected>--Select--</option>
                                        <option value="Google Meet">Google Meet</option>
                                        <option value="Zoom Meet">Zoom Meet</option>
                                        <option value="Microsoft Teams">Microsoft Teams</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Meeting Date</label>
                                    <input type="date" class="form-control" name="meeting_date" min="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Meeting Start Time</label>
                                    <select name="meeting_from_duration" class="form-control" required>
                                    <option value="" selected>--Select--</option>
                                    <?php $start =strtotime(date("01:00"));
                                    $end = strtotime('24:00');
                                    $range = array();
                                        while($start <= $end){ ?>
                                            <option value="{{date('h:i A',$start)}}">{{date('h:i A',$start)}}</option>
                                            <?php $start = strtotime('+15 minutes',$start);
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Meeting End Time</label>
                                    <select name="meeting_to_duration" class="form-control" required>
                                    <option value="" selected>--Select--</option>
                                    <?php $start =strtotime(date("01:00"));
                                    $end = strtotime('24:00');
                                    $range = array();
                                        while($start <= $end){ ?>
                                            <option value="{{date('h:i A',$start)}}">{{date('h:i A',$start)}}</option>
                                            <?php $start = strtotime('+15 minutes',$start);
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Candidate Email</label>
                                    <input type="text" class="form-control" name="candidate_email" value="{{$result->email}}" required>
                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Meeting Link And Description</label>
                                    <textarea type="hiden" name="meeting_description" value="@if(!empty($update->meeting_description)){{$update->meeting_description}}@endif" required></textarea>
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
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Interview List ➤ {{$result->salutation}} {{$result->first_name}} {{$result->middle_name}} {{$result->last_name}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Meeting Type</th>
                                    <th>Meeting Date</th>
                                    <th>Meeting Start Time</th>
                                    <th>Meeting End Time</th>
                                    <th>Candidate Email</th>
                                    <th>Schedule Date</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($history))
                                @foreach($history as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->meeting_type}}</td>
                                    <td>{{date_format(date_create($row->meeting_date),"d-M-Y")}}</td>
                                    <td>{{$row->from_meeting}}</td>
                                    <td>{{$row->to_meeting}}</td>
                                    <td>{{$row->candidate_email}}</td>
                                    <td>{{date_format(date_create($row->created_at),"d-M-Y H:i")}}</td>
                                    <td><a href="#" data-toggle="modal" data-target="#myModal" onclick="show_data({{$row->id}})"><i class="fa fa-eye"></i></a></td>
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
            <h4 class="modal-title">Meeting Link And Description</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="meeting_link_description">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
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
                $('#department_id').append('<option value="">Select Department</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
            }
        });
    }
    function get_designation() {
        var office_id = $('#office_id option:selected').data('id');
        var department_id = $('#department_id option:selected').data('id');
        $('#position_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-designation')}}",
            data: {
                office_id: office_id,
                department_id: department_id,
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#position_id').append('<option value="">Select Designation</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                }
            }
        });
    }
    $(document).ready(function(){
        var datatable = $('#examples').dataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
    function show_data(id){
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            url: "{{url('ajax/get-meeting-linkdata')}}",
            data: {id:id},
            success: function(xhr) {
                $('#meeting_link_description').html(xhr.data.meeting_link_description);
            }
        });
    }
    $(document).ready(function() {
        $(".add-more").click(function(){ 
            var html = $("#tab_logic").html();
            $(".after-add-more").after(html);
            $(".change").append("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
        });

        $("body").on("click",".remove",function(){ 
            $(this).parents("#tab_logic").remove();
        });
    });
</script>
<script>
    CKEDITOR.replace( 'meeting_description' );
</script>
@endsection('content')