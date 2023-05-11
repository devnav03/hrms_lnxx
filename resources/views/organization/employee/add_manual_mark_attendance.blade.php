@extends('layouts.organization.app')
@section('content')
<style>
    #searchResult li:hover {
        cursor: pointer;
        background-color: #0dcaf0;
    }
    #searchResult{
    list-style: none;
    padding: 0px;
    width: 91.3%;
    position: absolute;
    margin: 0;
    z-index: 99;
    }
    #searchResult li{
        background: cadetblue;
        padding: 4px;
        color:#fff;
        margin-bottom: 1px;
    }
    #searchResult li:nth-child(even){
        background: cadetblue;
        color: white;
    }
    #searchResult li:hover{
        cursor: pointer;
        background-color: #0dcaf0;
    }
    .clear{
        clear:both;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Manual Attendance</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('add-manual-mark-attendance')}}" method="POST">
                            @csrf
                            <div class="row">
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Start date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Select In Time</label>
                                        <input type="time" class="form-control" id="in_time" name="in_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>End date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Select Out Time</label>
                                        <input type="time" class="form-control" id="out_time" name="out_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <hr class="sel-emp" style="margin: 10px -30px 22px;">
                                    <label>Select Employees*:</label>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Office Name</label>
                                        <select class="form-control" id="office_id" name="office_id" required>
                                            <option value="">Select</option>
                                                @if(!empty($office_name))
                                                    @foreach($office_name as $row1)
                                                        <option value="{{$row1->id}}">{{$row1->office_name}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Department Name</label>
                                        <select class="form-control" id="department_id" name="department_id" required>
                                            <option value="">Select</option>
                                                @if(!empty($department_name))
                                                    @foreach($department_name as $row2)
                                                        <option value="{{$row2->id}}">{{$row2->department_name}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Search By Name or Id</label>
                                        <input type="hidden" value="@if(!empty($employee_name->id)){{$employee_name->id}}@endif" name="emp_id" id="emp_id">
                                        <input type="text" class="form-control" value="@if(!empty($employee_name->id)){{$employee_name->id}}@endif" name="emp_name" id="emp_name" placeholder="Search Employee Name" autocomplete="off">
                                        <ul id="searchResult"></ul>
                                        <div class="clear"></div>

                                        <!-- <label>Select Employee Name</label>
                                        <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                        <select class="form-control" id="user_id" name="user_id" required>
                                            <option value="">Select</option>
                                            @if(!empty($employee_name))
                                                @foreach($employee_name as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>  -->

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{url('manual-mark-attendance')}}" class="btn btn-primary btn-sm">Back</a>
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
    $(document).ready(function(){
        $("#emp_name").keyup(function(){
            var search = $(this).val();
            if(search != "" && search.length > 1){
                $("#searchResult").html("<li>"+search+"</li>");
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/search-employee-name')}}",
                    type: 'post',
                    data: {search:search},
                    dataType: 'json',
                    success:function(response){
                        var len = response.length;
                        $("#searchResult").empty();
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#searchResult").append("<li value='"+id+"'>"+name+"</li>");
                        }
                        $("#searchResult li").bind("click",function(){
                            $("#emp_id").val($(this).val());
                            $("#emp_name").val($(this).text());
                            $("#searchResult").empty();
                        });
                    }
                });
            }
        });
    });
</script>

@endsection('content')