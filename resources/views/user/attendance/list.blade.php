@extends('layouts.user.app')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-10 col-6">
                                <h5 class="">Attendance Details</h5>
                            </div>
                            <div class="col-md-2 col-6">
                                @if(!empty($attendance))
                                    <span class="btn btn-primary btn-sm"  onclick="alertmsg()"> Mark Attendance</span>
                                @else
                                    <span class="btn btn-primary btn-sm open-camera" onclick="getLocation()" data-toggle="modal" data-target="#myModal"> Mark Attendance</span>
                                    <span class="alert-msg"></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Total Working Hr</th>
                                    <th>In Image</th>
                                    <th>Out Image</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
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
                <h4 class="modal-title">Mark Attendance</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="my_camera" style="width:100%"></div>
                            <input type=button class="btn btn-primary btn-sm btn-block" value="Take Snapshot" onClick="take_snapshot()">
                            <input type="hidden" name="snapshot" id="snapshot" class="image-tag">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>
                        <div class="col-md-6">
                            <div id="results"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
                    <button class="btn btn-success btn-sm" id="show_button" style="display:none;">Submit</button>
                </div>
            </form>
            </div>
            
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                scrollX: true,
                ajax: "{{url('ajax/employee-attendances')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'in_time'},
                    {data:'out_time'},
                    {data:'total_time'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a href="{{asset("employee/attendance")}}/'+data.in_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.in_image+'" style="width: 100px;height: 89px;border: 1px solid #8080803b;padding: 8px;"/></a>';
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            if(data.out_image != null){
                            return '<a href="{{asset("employee/attendance")}}/'+data.out_image+'" target="_blank"><img src="{{asset("employee/attendance")}}/'+data.out_image+'" style="width: 100px;height: 89px;border: 1px solid #8080803b;padding: 8px;"/></a>';
                        } else {
                            return 'N/A';
                        }
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.created_at);
                        }
                    },
                ]
            });
        });
    </script>
    <script type="text/javascript">
        function CheckEmail(email) {
            $('.organisation_button').removeAttr("disabled");
            var check_container = $('.checking_email');
            var check_input = email;
            if(check_input == '') {
                check_container.empty();
                return false;
            }
            check_container.removeClass('text-danger').removeClass('text-primary').html('<span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
            if(check_input.length >= 4){
                $.ajax({  
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url : "{{url('ajax/check-email')}}", 
                    data: "email="+ check_input,
                    success: function(data){  
                        if(data.status == 200) {
                            check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('text-danger').addClass('text-primary');
                            $('.employee_button').removeAttr("disabled");
                        } else if(data.status == 404) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('text-primary').addClass('text-danger');
                            $('.employee_button').attr("disabled", true);
                        } else if(data.status == 401) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('text-primary').addClass('text-danger');
                            $('.employee_button').attr("disabled", true);
                        }
                    }
                }); 
            }
        }
    </script>
    <script>
    var x = document.getElementById("demo");
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }
    function showPosition(position) {
        $('#latitude').val(position.coords.latitude);
        $('#longitude').val(position.coords.longitude);
    }
    </script>
    <script language="JavaScript">
        $(document).ready(function() {
            $(".open-camera").click(function(){ 
                Webcam.set({
                    width: 390,
                    height: 330,
                    image_format: 'jpeg',
                    jpeg_quality: 100
                });
                Webcam.attach('#my_camera');
            });
        });
        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML='<img style="width: 100%;height: 100%;border-radius: 0.25rem;object-fit: contain;border: 1px solid #e3d4d4;" src="'+data_uri+'"/>';
            });
            $('#show_button').show();
        }
    </script>
    <script>
    $(function () {
        $('form').on('submit', function (e) {
            var spinner = $('#loader');
            spinner.show();
            var latitude = $('#latitude').val();
            var snapshot = $('#snapshot').val();
            e.preventDefault();
            if(latitude!='' && snapshot!=''){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    type: 'POST',
                    url : "{{url('ajax/mark-attendance')}}", 
                    data: $('form').serialize(),
                    success:function(xhr){
                        if(xhr.status==200){
                            toastr.success(xhr.message);
                            $('#example').DataTable().ajax.reload()
                            if(xhr.attendance=='OUT'){
                                $('.alert-msg').html("<span class='btn btn-primary btn-sm' onclick='alertmsg()'> Mark Attendance</span>");
                                $('.open-camera').hide();
                            }
                        }else{
                            toastr.error(xhr.message);
                        }
                        spinner.hide();
                        $("#results").html('');
                        $("#myModal").modal('hide');
                        $("#snapshot").val('');
                    }
                });
            }else{
                toastr.error('Please enable location and camera');
                spinner.hide();
            }
        });
    });
    function alertmsg(){
        toastr.error('You Have Marked Attendance Out');
    }
    </script>
    @endsection('content')