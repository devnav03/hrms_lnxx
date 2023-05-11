@extends('layouts.organization.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 2.2rem !important;
    }
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaaaaa73 !important;
        border-radius: 0px !important;
    }
    .select2-container{
        width:100% !important;
    }
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
                        <h5 class="">Search Employee Attendance</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row">
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
                                    <input type="text" class="form-control" value="@if(!empty($employee_name->name)){{$employee_name->name}}@endif" name="emp_name" id="emp_name" placeholder="Search Employee Name" autocomplete="off">
                                    <ul id="searchResult"></ul>
                                    <div class="clear"></div>        
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

        <div class="row mb-2">
            <div class="col-md-12  text-right">
                <a href="{{url('add-manual-mark-attendance')}}" class="btn btn-success btn-sm">Full Attendance Marking</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Attendance List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Name</th>
                                    <th>Office Name</th>
                                    <th>Department Name</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>


    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Missed Punch</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id=""></div>
                    <form id="manual_punch">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                    <input type="hidden" class="form-control" name="get_id" id="get_id" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Out Time</label>
                                    <input type="time" class="form-control" id="out_time" name="out_time" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-sm mr-2" onclick = "show_data_sub()">Submit</button>
                            </div>
                            </form>
                        </div>
                    
                </div>
                <div class="modal-footer" id="">
                    
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
<script>
      $(function () {
        $('form').on('submit', function (e) {
        var spinner = $('#loader');
        spinner.show();
          e.preventDefault();
            $('#example').dataTable().fnClearTable();
            $('#example').dataTable().fnDraw();
            $('#example').dataTable().fnDestroy();
            var datatable = $('#example').dataTable({
                "ajax": function (data, callback, settings) {
                    $.ajax({
                    url: "{{url('ajax/get-employee-attendance-data')}}",
                    dataType:"json",
                    type: 'POST',
                    data: $('form').serialize(),
                        success: function(data) {
                            callback(data);
                        }
                    });
                },
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'names'},
                    {data:'office_name'},
                    {data:'department_name'},
                    {data:'in_time'},
                    {data: null,
                        mRender:function (data,type,row){
                        if(data.out_time!=null){
                            return '<span>'+data.out_time+'</span>';
                        }else{
                            return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="btn btn-danger btn-sm mx-2">Missed Punch</a>';
                        }
                    }
                },

                ]
            });
            spinner.hide();
        });
      });
    </script>
    <script>
        $(document).ready(function() {
            $('.emp_name').select2();
        });
    </script>
    <script>
        function show_data(id){
            var id = id;
            $("#get_id").val(id);
        }
    </script>
    <script>
        function show_data_sub(){
            $.ajax({
               type:'GET',
               data:$('#manual_punch').serialize(),
               url:'{{url('add-missed-attend')}}',
               success:function(data) {
                  location.reload();
               }
            });
        }
    </script>
@endsection('content')