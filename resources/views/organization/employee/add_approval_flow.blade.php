@extends('layouts.organization.app')
@section('content')
<style>.table-hover tbody tr{background: #eaeaf1;}</style>
<?php $user_id = Auth::user()->id;?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Leave Approval Flow <apan id="flow_name_preview"></apan> <apan id="flow_office_preview"></apan></h5>
                    </div>
                    <div class="card-body">
                        <div class="row hide_flow_name">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Flow Name *</label>
                                    <input type="hidden" name="flow_id" id="flow_id" class="form-control" value="">
                                    <input type="hidden" name="flow_office_id" id="flow_office_id" class="form-control" value="">
                                    <input type="text" class="form-control" id="flow_name" name="flow_name" value="<?php echo !empty($flow->flow_name) ? $flow->flow_name:'';?>" placeholder="Enter Flow Name" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="">&nbsp;</label><br/>
                                <span class="btn btn-primary btn-sm add_flow_name">Add</span>
                            </div>  
                        </div>
                        
                        <div class="card mb-4 leave-flow-data">

                            <div class="card" >
                            <div style="padding: 7px;">
                                <div class="row" >
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Office *</label>
                                            <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                            <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id();">
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
                                            <select class="form-control" id="department_id" name="department_id" required onchange="get_designation();">
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
                                            <label>Designation *</label>
                                            <select class="form-control" id="position_id" name="position_id" required>
                                                @if(!empty($position))
                                                    <option value="">Select Position</option>
                                                    @foreach($position as $row2)
                                                        <option value="{{$row2->id}}" data-id="{{$row2->id}}" @if(!empty($update->position_id)) @if($update->position_id==$row2->id) selected @endif @endif>{{$row2->position_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Type Of Leave  *</label>
                                            <select class="form-control" id="leave_type" name="leave_type" required>
                                                <option value="">--Select--</option>
                                                @if(!empty($leave_name))
                                                    @foreach($leave_name as $row)
                                                        <option value="{{$row->id}}">{{$row->emp_type}} &#10148; {{$row->name}} ( {{$row->total_leave}} )</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>





                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="">Leave Flow</h5>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Office</th>
                                                <th>Department</th>
                                                <th>Designation</th>
                                                <th>Type Of Leave</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <!-- <tbody id="approval_flow_data"> -->
                                    <tbody> 
                                     @if(!empty($leave_flow_result))
                                     @foreach($leave_flow_result as $leave_result)
                                      <tr>
                                        <td>{{$leave_result->id}}</td>
                                        <td>{{$leave_result->flow_name}} ➤ {{$leave_result->office_name}}</td>
                                        <td>{{$leave_result->department_name}}</td>
                                        <td>{{$leave_result->position_name}}</td>
                                        <td>{{$leave_result->emp_type}} ➤ {{$leave_result->name}}</td>
                                         <td><a href="{{url('delete-leave-flow')}}/{{$leave_result->id}}" class="text-danger delete-button"><i class="fa fa-trash" title="Delete"></i></a></td>
                                        </tr>
                                        @endforeach
                                      @endif    
                                            
                                        </tbody>
                                    </table>
                                </div>

                               <!-- <div class="col-md-10" style="margin-top:5px;margin-bottom:5px; margin-left:40px;">  <a class="btn btn-primary float-right btn-sm mx-1 remove_flow add_flowname_button">Save</a> </div> -->
                               <div class="col-md-10"></div>
                                <div class="col-md-2" style="margin-top:5px;margin-bottom:5px; margin-left: -60px!important;">
                                        <a class="btn btn-success float-right btn-sm mx-1 remove_flow add_flowname_button">&nbsp;+ Add More</a>
                                    </div>
                            </div>
                        </div>
                 <!--        <div class="card after-add-more" style="display: none;">
                            
                        </div> -->
                        <div class="card mb-4 approval-authority-view">

                        <div style="padding: 7px;">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Office *</label>
                                            <select class="form-control" id="authority_office" name="authority_office" required onchange="GetOfficeId()">
                                                @if(!empty($office))
                                                    <option value="">--Select--</option>
                                                    @foreach($office as $row)
                                                        <option value="{{$row->id}}" data-id="{{$row->id}}">{{$row->office_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Department *</label>
                                            <select class="form-control" id="authority_department" name="authority_department" required onchange="GetDesignation()">
                                                @if(!empty($department))
                                                    <option value="">--Select--</option>
                                                    @foreach($department as $row1)
                                                        <option value="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Designation *</label>
                                            <select class="form-control" id="authority_position" name="authority_position" onchange="GetEmployees();" required>
                                                @if(!empty($position))
                                                    <option value="">Select Position</option>
                                                    @foreach($position as $row2)
                                                        <option value="{{$row2->id}}" @if(!empty($update->position_id)) @if($update->position_id==$row2->id) selected @endif @endif>{{$row2->position_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Select Employee Name</label>
                                            <select class="form-control" id="authority_user" name="authority_user" required>
                                                <option value="">--Select--</option>
                                                @if(!empty($users))
                                                    @foreach($users as $row3)
                                                        <option value="{{$row3->id}}" data-id="{{$row3->id}}"  @if(!empty($update->user_id)) @if($update->user_id==$row3->id) selected @endif @endif>{{$row3->name}} ( {{$row3->employee_code}} )</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="">Approval Authority</h5>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Office</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Employee Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody id="authority_user_data"> -->
                                     <tbody> 
                                     @if(!empty($approval_flow_result))
                                     @foreach($approval_flow_result as $approval_result)
                                      <tr>
                                        <td>{{$approval_result->id}}</td>
                                        <td>{{$approval_result->office_name}}</td>
                                        <td>{{$approval_result->department_name}}</td>
                                        <td>{{$approval_result->position_name}}</td>
                                        <td>{{$approval_result->name}}</td>
                                         <td><a href="{{url('delete-approval-authority')}}/{{$approval_result->id}}/{{$approval_result->flow_id}}" class="text-danger delete-button"><i class="fa fa-trash" title="Delete"></i></a></td>
                                        </tr>
                                        @endforeach
                                      @endif     
                                        
                                    </tbody>
                                </table>
                                  </div>
                               <!-- <div class="col-md-9" style="margin-top:5px;margin-bottom:5px; margin-left:55px !important;"> <a class="btn btn-primary float-right remove_approval add-more btn-sm save_approval">Save</a></div> -->
                            <div class="col-md-9"></div>

                            <div class="col-md-3" style="margin-top:5px;margin-bottom:5px; margin-left: -70px!important;">
                            <a class="btn btn-success float-right add-more btn-sm remove_approval save_approval">+ Add Approval Authority</a></div>
                            </div>
                        </div>
                        <div class="card setting-approval" style="display:none">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="">Setting</h5>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <a class="btn btn-success float-right add-more btn-sm save_settings">+ Add Approval Authority</a>
                                    </div> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email Notification For Approve</label>
                                            <div class="form-check"><label class="switch">
                                                <input name="email_for_approve" id="email_for_approve" type="checkbox" value="email_for_approve" class="email_for_approve"><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email Notification For Reject</label>
                                            <div class="form-check"><label class="switch">
                                                <input name="email_for_reject" id="email_for_reject" type="checkbox" value="email_for_reject" class="email_for_reject"><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>SMS Notification For Approve</label>
                                            <div class="form-check"><label class="switch">
                                                <input name="sms_for_approve" id="sms_for_approve" type="checkbox" value="sms_for_approve" class="sms_for_approve"><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>SMS Notification For Reject</label>
                                            <div class="form-check"><label class="switch">
                                                <input name="sms_for_reject" id="sms_for_reject" type="checkbox" value="sms_for_reject" class="sms_for_reject"><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>App Notification For Approve</label>
                                            <div class="form-check"><label class="switch">
                                                <input name="app_for_approve" id="app_for_approve" type="checkbox" value="app_for_approve" class="app_for_approve"><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>App Notification For Reject</label>
                                            <div class="form-check"><label class="switch">
                                                <input name="app_for_reject" id="app_for_reject" type="checkbox" value="app_for_reject" class="app_for_reject"><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="btn btn-primary text-center save-settings btn-sm">Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($flow_master)) @foreach($flow_master as $flows)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class=""><i class="fa fa-sitemap"></i> {{$flows->flow_name}} ➤
                                <?php 
                                $approval = App\Models\ApprovalFlow::select('office_id')->where('flow_id',$flows->id)->first();
                                if(!empty($approval)){
                                    $office = App\Models\OfficeMaster::select('id','office_name')->where('id',$approval->office_id)->first();
                                    if(!empty($office)) { echo $office->office_name;}
                                }?>
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <a class="text-primary float-right btn-sm" data-toggle="modal" data-target="#myModal" onclick="get_flow({{$flows->id}})"><i class="fa fa-eye" style="font-size:18px"></i></a>
                                <a class="text-danger float-right btn-sm"><i class="fa fa-trash" style="font-size:18px"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="display: block;">
                        <?php $select = App\Models\NotificationSetting::where('flow_id',$flows->id)->where('flow_type','leave-flow')->first();
                        ?>
                        <table class="table table-hover">
                            <tr>
                                <td><b>Email Notification On Approve :</b> <?php if($select->email_for_approve==1){ echo 'Yes';}else{ echo 'No';}?></td>
                                <td><b>Email Notification On Reject :</b> <?php if($select->email_for_reject==1){ echo 'Yes';}else{ echo 'No';}?></td>
                            </tr>
                            <tr>
                                <td><b>SMS Notification On Approve :</b> <?php if($select->sms_for_approve==1){ echo 'Yes';}else{ echo 'No';}?></td>
                                <td><b>SMS Notification On Reject :</b> <?php if($select->sms_for_reject==1){ echo 'Yes';}else{ echo 'No';}?></td>
                            </tr>
                            <tr>
                                <td><b>App Notification On Approve :</b> <?php if($select->app_for_approve==1){ echo 'Yes';}else{ echo 'No';}?></td>
                                <td><b>App Notification On Reject :</b> <?php if($select->app_for_reject==1){ echo 'Yes';}else{ echo 'No';}?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer" style="display: block;"></div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Leave Approval Flow <span id="flow_name_view"></span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Type Of Leave</th>
                                </tr>
                            </thead>
                            <tbody id="approval_flow_data_view">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Employee Name</th>
                                </tr>
                            </thead>
                            <tbody id="authority_user_data_view">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="leave_id">
            </div>
        </div>
    </div>
</div>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        function get_office_id() {
            var flow_office_id = $('#flow_office_id').val();
            var department_id = $('#office_id option:selected').data('id');
            if(flow_office_id==''){
                if($('.remove_flow').hasClass('change-office')){
                    $('.remove_flow').addClass('add_flowname_button');
                    $('.remove_flow').removeClass('change-office');
                }
                OfficeFun(department_id);
            }else{
                if(flow_office_id==department_id){
                    if($('.remove_flow').hasClass('change-office')){
                        $('.remove_flow').addClass('add_flowname_button');
                        $('.remove_flow').removeClass('change-office');
                    }
                    OfficeFun(department_id);
                }else{
                    toastr.error('Office Canot be changed');
                    $('.remove_flow').removeClass('add_flowname_button');
                    $('.remove_flow').addClass('change-office');
                }
            }
        }
        function OfficeFun(department_id){
            var spinner = $('#loader');
            spinner.show();
            $('#department_id').empty();
            $('#designation_id').empty();
            $.ajax({
                type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-department-name')}}",
                data:{department_id: department_id},
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }spinner.hide();
                }
            });
        }
        function get_designation() {
            var spinner = $('#loader');
            spinner.show();
            var office_id = $('#office_id option:selected').data('id');
            var department_id = $('#department_id option:selected').data('id');
            $('#position_id').empty();
            $('#leave_type').empty();
            $.ajax({
                type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-designation')}}",
                data: {office_id: office_id,department_id: department_id},
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#position_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                    }spinner.hide();
                }
            });
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-leave')}}",
                data: {office_id: office_id,department_id: department_id},
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#leave_type').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#leave_type').append('<option value="'+datas[i].id+'">'+datas[i].emp_type+' &#10148; '+datas[i].name+' ( '+datas[i].total_leave+' )</option>');
                    }spinner.hide();
                }
            });
        }

        function GetOfficeId() {
            var flow_office_id = $('#flow_office_id').val();
            var department_id = $('#authority_office').val();
            if(flow_office_id==''){
                if($('.remove_approval').hasClass('change-office')){
                    $('.remove_approval').addClass('save_approval');
                    $('.remove_approval').removeClass('change-office');
                }
                OfficeFunAuth(department_id);
            }else{
                if(flow_office_id==department_id){
                    if($('.remove_approval').hasClass('change-office')){
                        $('.remove_approval').addClass('save_approval');
                        $('.remove_approval').removeClass('change-office');
                    }
                    OfficeFunAuth(department_id);
                }else{
                    toastr.error('Office Canot be changed');
                    $('.remove_approval').removeClass('save_approval');
                    $('.remove_approval').addClass('change-office');
                }
            }
        }
        function OfficeFunAuth(department_id){
            var spinner = $('#loader');
            spinner.show();
            $('#authority_department').empty();
            $.ajax({
                type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-department-name')}}",
                data: {department_id:department_id},
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#authority_department').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#authority_department').append('<option value="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }spinner.hide();
                }
            });
        }
        function GetDesignation() {
            var spinner = $('#loader');
            spinner.show();
            var office_id = $('#authority_office').val();
            var department_id = $('#authority_department').val();
            $('#authority_position').empty();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-designation')}}",
                data: {office_id: office_id,department_id: department_id,},
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#authority_position').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#authority_position').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                    }spinner.hide();
                }
            });
        }
        function GetEmployees(){
            var spinner = $('#loader');
            spinner.show();
            var office_id = $('#authority_office').val();
            var department_id = $('#authority_department').val();
            var position_id = $('#authority_position').val();
            $('#authority_user').empty();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-employee-against-position')}}",
                data: {
                    office_id: office_id,
                    department_id: department_id,
                    position_id: position_id,
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#authority_user').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#authority_user').append('<option value="'+datas[i].id+'">'+datas[i].name+'</option>');
                    }spinner.hide();
                }
            });
        }
    </script>

    
    <script>
        $(document).ready(function() {
            $(".add_flowname_button").click(function(){ 
                $('.show_datas').show();
                $('.add_flowname_button').removeClass('btn-success');
                $('.add_flowname_button').addClass('btn-primary');
                if($('.add_flowname_button').hasClass('btn-primary')){
                    var spinner = $('#loader');
                    spinner.show();
                    var flow_id = $('#flow_id').val();
                    var office_id = $('#office_id').val();
                    var department_id = $('#department_id').val();
                    var position_id = $('#position_id').val();
                    var leave_type = $('#leave_type').val();
                    if(office_id!='' && department_id!='' && position_id!='' && leave_type!=''){
                        $.ajax({
                            type: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                            url: "{{url('ajax/save-approval-flow')}}",
                            data: {
                                flow_id: flow_id,
                                office_id: office_id,
                                department_id: department_id,
                                position_id: position_id,
                                leave_type: leave_type,
                            },
                            success: function(xhr) {
                                if(xhr.status==200){
                                    $('#approval_flow_data').empty();
                                    var html = '';
                                    toastr.success(xhr.msg);
                                    $('.after-add-more').hide();
                                    $('.add_flowname_button').addClass('btn-success');
                                    $('.add_flowname_button').removeClass('btn-primary');
                                    var datas = xhr.datas;
                                    if(datas.length>0){
                                        for (var i = 0; i < datas.length; i++) {
                                            html += '<tr><td>'+(i+1)+'</td>'+
                                                '<td>'+datas[i].office_name+'AAA</td>'+
                                                '<td>'+datas[i].department_name+'</td>'+
                                                '<td>'+datas[i].position_name+'</td>'+
                                                '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                                                '<td><a href="{{url("delete-leave-flow")}}/'+datas[i].id+'" class="text-danger delete-button"><i class="fa fa-trash" title="Delete"></i></a></td>'+
                                            '</tr>';
                                        }
                                        $('#approval_flow_data').append(html);
                                    }
                                    $('.show_datas').hide();
                                    $('.approval-authority-view').show();
                                }else{
                                    toastr.error(xhr.msg);
                                }
                                if(xhr.office.length>0){
                                    $('#flow_office_id').val(xhr.office.id);
                                    $('#flow_office_preview').text('➤ '+xhr.office.office_name);
                                }
                                spinner.hide();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                spinner.hide();
                            }
                        });
                    }else{
                        spinner.hide();
                        toastr.error('Please enter flow');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).on('click','.save-settings',function(){
            swal({
                title: "Are you sure?",
                text: "Do you want to save ",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var flow_id = $('#flow_id').val();
                    if ($('#email_for_approve').prop('checked')==true){ 
                        var email_for_approve = $('#email_for_approve').val();
                    }else{
                        var email_for_approve = '';
                    }
                    if ($('#email_for_reject').prop('checked')==true){ 
                        var email_for_reject = $('#email_for_reject').val();
                    }else{
                        var email_for_reject = '';
                    }
                    if ($('#sms_for_approve').prop('checked')==true){ 
                        var sms_for_approve = $('#sms_for_approve').val();
                    }else{
                        var sms_for_approve = '';
                    }
                    if ($('#sms_for_reject').prop('checked')==true){ 
                        var sms_for_reject = $('#sms_for_reject').val();
                    }else{
                        var sms_for_reject = '';
                    }
                    if ($('#app_for_approve').prop('checked')==true){ 
                        var app_for_approve = $('#app_for_approve').val();
                    }else{
                        var app_for_approve = '';
                    }
                    if ($('#app_for_reject').prop('checked')==true){ 
                        var app_for_reject = $('#app_for_reject').val();
                    }else{
                        var app_for_reject = '';
                    }
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        url: "{{url('ajax/save-settings')}}",
                        data: {
                            flow_id: flow_id,
                            email_for_approve: email_for_approve,
                            email_for_reject: email_for_reject,
                            sms_for_approve: sms_for_approve,
                            sms_for_reject: sms_for_reject,
                            app_for_approve: app_for_approve,
                            app_for_reject: app_for_reject,
                        },
                        success: function(xhr) {
                            swal(xhr.msg, "Succesfully "+xhr.msg, "success");
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Something Went to Wrong!", "Please try again", "error");
                        }
                    });
                }
            });
        });
        $(document).ready(function() {
            $(".remove_flow").click(function(){ 
                if($('.remove_flow').hasClass('change-office')){
                    toastr.error('Office Canot be changed');
                }
            }); 
            $(".remove_approval").click(function(){ 
                if($('.remove_approval').hasClass('change-office')){
                    toastr.error('Office Canot be changed');
                }
            }); 
        }); 
        $(document).ready(function() {
            $(".add-more").click(function(){ 
                $('.after-add-more').show();
                $('.save_approval').removeClass('btn-success');
                $('.save_approval').addClass('btn-primary');
                if($('.save_approval').hasClass('btn-primary')){
                    var flow_id = $('#flow_id').val();
                    var office_id = $('#authority_office').val();
                    var department_id = $('#authority_department').val();
                    var position_id = $('#authority_position').val();
                    var authority_user = $('#authority_user').val();
                    if(office_id!='' && department_id!='' && position_id!='' && authority_user!=''){
                        var spinner = $('#loader');
                        spinner.show();
                        $.ajax({
                            type: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                            url: "{{url('ajax/save-authority-admin')}}",
                            data: {
                                flow_id: flow_id,
                                office_id: office_id,
                                department_id: department_id,
                                position_id: position_id,
                                authority_user: authority_user,
                            },
                            success: function(xhr) {
                                if(xhr.status==200){
                                    $('#authority_user_data').empty();
                                    var html = '';
                                    toastr.success(xhr.msg);
                                    $('.after-add-more').hide();
                                    $('.save_approval').addClass('btn-success');
                                    $('.save_approval').removeClass('btn-primary');
                                    $('#authority_department').empty();
                                    $('#authority_position').empty();
                                    $('#authority_user').empty();
                                    var datas = xhr.datas;
                                    if(datas.length>0){
                                        for (var i = 0; i < datas.length; i++) {
                                            html += '<tr><td>'+(i+1)+'</td>'+
                                                '<td>'+datas[i].office_name+'</td>'+
                                                '<td>'+datas[i].department_name+'</td>'+
                                                '<td>'+datas[i].position_name+'</td>'+
                                                '<td>'+datas[i].name+'</td>'+
                                                '<td><a href="{{url("delete-approval-authority")}}/'+datas[i].id+'/'+datas[i].flow_id+'" class="text-danger delete-button"><i class="fa fa-trash" title="Delete"></i></a></td>'+
                                            '</tr>';
                                        }
                                        $('#authority_user_data').append(html);
                                        $('.setting-approval').show();
                                    }
                                }else{
                                    toastr.error(xhr.msg);
                                }spinner.hide();
                            }
                        });
                    }else{
                        toastr.error('Please enter approval');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".add_flow_name").click(function(){ 
                var flow_name = $('#flow_name').val();
                if(flow_name!=''){
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        type: 'POST',
                        url : "{{url('ajax/save-flow-name')}}", 
                        data: {flow_name:flow_name},
                        success:function(xhr){
                            $('#approval_flow_data').empty();
                            $('#authority_user_data').empty();
                            $('.leave-flow-data').show();
                            var html = '';
                            var html1 = '';
                            if(xhr.status==200){
                                toastr.success(xhr.msg);
                            }else{
                                toastr.error(xhr.msg);
                            }
                            $('#flow_id').val(xhr.flow.id);
                            $('#flow_name_preview').text('➤ '+xhr.flow.flow_name);
                            if(xhr.office.id!=null){
                                $('#flow_office_preview').text('➤ '+xhr.office.office_name);
                                $('#flow_office_id').val(xhr.office.id);
                            }
                            var datas = xhr.datas;
                            for (var i = 0; i < datas.length; i++) {
                                html += '<tr><td>'+(i+1)+'</td>'+
                                    '<td>'+datas[i].office_name+'</td>'+
                                    '<td>'+datas[i].department_name+'</td>'+
                                    '<td>'+datas[i].position_name+'</td>'+
                                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                                    '<td><a href="{{url("delete-leave-flow")}}/'+datas[i].id+'" class="text-danger delete-button"><i class="fa fa-trash" title="Delete"></i></a></td>'+
                                '</tr>';
                            }
                            $('#approval_flow_data').append(html);
                            if(datas.length>0){
                                $('.approval-authority-view').show();
                                var authorities = xhr.authorities;
                                for (var i = 0; i < authorities.length; i++) {
                                    html1 += '<tr><td>'+(i+1)+'</td>'+
                                        '<td>'+authorities[i].office_name+'</td>'+
                                        '<td>'+authorities[i].department_name+'</td>'+
                                        '<td>'+authorities[i].position_name+'</td>'+
                                        '<td>'+authorities[i].name+'</td>'+
                                        '<td><a href="{{url("delete-approval-authority")}}/'+authorities[i].id+'/'+authorities[i].flow_id+'" class="text-danger delete-button"><i class="fa fa-trash" title="Delete"></i></a></td>'+
                                    '</tr>';
                                }
                                $('#authority_user_data').append(html1);
                                $('.setting-approval').show();
                            }show_datas();spinner.hide();
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function show_datas(){
            $('.hide_flow_name').hide();
        }
    </script>
    <script>
        function get_flow(id){
            $('#approval_flow_data_view').empty();
            $('#authority_user_data_view').empty();
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-leave-flow-data')}}",
                data: {id: id},
                success: function(xhr) {
                    var html='';
                    var html1='';
                    if(xhr.status==200){
                        $('#flow_name_view').text('➤ '+xhr.flow.flow_name);
                        var datas = xhr.datas;
                        for (var i = 0; i < datas.length; i++) {
                            html += '<tr><td>'+(i+1)+'</td>'+
                                '<td>'+datas[i].office_name+'</td>'+
                                '<td>'+datas[i].department_name+'</td>'+
                                '<td>'+datas[i].position_name+'</td>'+
                                '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                            '</tr>';
                        }
                        var authorities = xhr.authorities;
                        for (var i = 0; i < authorities.length; i++) {
                            html1 += '<tr><td>'+(i+1)+'</td>'+
                                '<td>'+authorities[i].office_name+'</td>'+
                                '<td>'+authorities[i].department_name+'</td>'+
                                '<td>'+authorities[i].position_name+'</td>'+
                                '<td>'+authorities[i].name+'</td>'+
                            '</tr>';
                        }
                    }
                    $('#approval_flow_data_view').append(html);
                    $('#authority_user_data_view').append(html1);
                    spinner.hide();
                }
            });
        }
    </script>
@endsection('content')