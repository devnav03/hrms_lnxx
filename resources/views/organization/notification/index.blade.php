@extends('layouts.organization.app')
@section('content')
<link rel="stylesheet" href="css/select2.min.css" type="text/css">
<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> -->
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
.custom-switch .custom-control-label::before {
    background-color: #aa66cc!important;
    color: white;
    border: #aa66cc solid 1px!important;
}
.custom-switch .custom-control-label::after{
    background-color: #ffffff;
}
</style>
<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
           <!--      <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li> -->
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#" style="font-size: 1.125rem; color: #000; font-weight: 600;">Notification</a>
                </li>
            </ol>
        </nav>
@if(session()->has('notification_created'))
    <li class="alert alert-success" style="list-style: none; margin-top: 25px;">Notification successfully created</li>
@endif  
</div>
    
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation" action="{{ route('notification.store') }}" method="POST" novalidate="" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Select Notication Type</label>
                                        <select class="form-control" name="notication_type" id="notication_type" onchange="getTemplates()" required="">
                                            <option value="">Select </option>   
                                            <option value="1">Office Wise</option>
                                            <option value="2">Department Wise</option>
                                            <option value="3">Designation Wise</option>
                                            <option value="4">Specific Employees</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label id="dataname">Select</label>
                                        <span class="selectbox_single">
                                            <select  class="select2-example form-control" name="datatypes" id="datatypes">
                                            </select>
                                        </span>

                                        <span class="selectbox_multiple" style="display:none">
                                            <select class="select2-example form-control" name="datatypes1[]" id="datatypes1" multiple>
                                            </select>
                                        </span>

                                        <span class="textarea_employees" style="display:none">
                                            <textarea class="form-control" maxlength="225" name="datatypes2"></textarea>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label>Message Title ( <span id="charsLeftTitle">60</span> )</label>
                                        <input type="text" maxlength="60" onkeyup="countCharTitle(this)" class="form-control" name="title" placeholder="Message Title" required="">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Description ( <span id="charsLeft">150</span> )</label>
                                        <textarea class="form-control" onkeyup="countChar(this)" maxlength="150" name="description" required=""></textarea>
                                    </div>
                                    
                                </div>
                                <button class="btn btn-primary" type="submit" style="background: #1877f2; border-color: #1877f2; padding: 10px 20px;">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex" style="margin-bottom: 15px;">
                             <h6 class="card-title w-50">Pending Notification List</h6>
                             <span class="w-50">
                             <a href="{{ route('notification-history') }}" class="float-right text-white btn-sm status_checks btn btn-success" style="height:30px;">View Notification History</a>
                            </span>
                            </div>
                            <table id="example1" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                                        <th>Message Title</th>
                                        <th>Notication Type</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Created date & By</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                        <tr>
                                            <td><?=$sr++;?></td>
                                            <td><?=$row->title;?></td>
                                            <td><?php if($row->notication_type==1){ echo 'Office Wise';}elseif($row->notication_type==2){ echo 'Department Wise';}elseif($row->notication_type==3){ echo 'Designation Wise';}elseif($row->notication_type==4){ echo 'Specific Employees';}elseif($row->notication_type==5){ echo 'Group Wise';}elseif($row->notication_type==6){ echo 'Title Wise';}elseif($row->notication_type==7){ echo 'Client visit allowed';}?></td>
                                            <td>
                                                <?php if($row->notication_type==1){
                                                    $office = \DB::table('office_masters')->where('id', $row->master_id)->select('office_name')->first();

                                                   echo $office->office_name;
                                                }
                                                if($row->notication_type==2){
                                                    $office = \DB::table('department_masters')->where('id', $row->master_id)->select('department_name')->first();

                                                   echo $office->department_name;
                                                }
                                                if($row->notication_type==3){
                                                    $office = \DB::table('position_masters')->where('id', $row->master_id)->select('position_name')->first();

                                                   echo $office->position_name;
                                                }
                                                if($row->notication_type==4){

                                                $variable=explode(",", $row->employee_id);
                                                //$code=implode("','", $variable);

                                                foreach ($variable as $key => $value) {
                                                    $emp_code = \DB::table('employee_infos')->where('employee_code', $value)->select('user_id')->first();  
                                                $user_code = \DB::table('users')->where('id', @$emp_code->user_id)->select('name')->first(); 
                                                if($key == 0){
                                                    echo @$user_code->name;
                                                } else {
                                                    echo ", ". @$user_code->name;
                                                }

                                                }

                                                } if($row->notication_type==5){
                                               
                                                } if($row->notication_type==6){
                                             
                                                } if($row->notication_type==7){
                                                 
                                                } ?>
                                            </td>
                                          
                                            <td>
                                                <input type="hidden" name="xid" value="<?=$row->status;?>" class="xid_<?=$row->id?>">
                                                <button type="button" onclick="updateStatus(<?=$row->id?>)" id="<?=$row->id?>" class="text-white btn-sm status_checks btn <?php if ($row->status == 1) { ?> btn-success <?php } else { ?> btn-danger <?php } ?> " value="<?=$row->id;?>">
                                                    <?php if ($row->status == 1){ ?> Active

                                                   <?php } else { ?> Inactive <?php } ?>
                                                </button>
                                            </td> 
                                            <td><?php $created_date=date_create($row->created_at); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                                                <?php
                                
                                                     echo $row->user_name; 
                                            
                                                ?>
                                            </td>
                                        <!--     <td>
                                                <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>Action 
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                                </div>
                                            </td> -->
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection