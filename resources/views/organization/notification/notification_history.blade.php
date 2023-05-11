@extends('layouts.organization.app')
@section('content')
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
#example1_paginate{
    display: none;
}
</style>
<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('notification.index') }}">Notification</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Notification History</a>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12" style="margin-bottom: 20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                             <h6 class="card-title w-50">Notification History</h6>
                             <span class="w-50">
                           
                            </span>
                            </div>
                            <div style="overflow-x:auto;">
                            <table id="example1" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important; text-align: center;">SNo.</th>
                                        <th>Message ID</th>
                                        <th>Notication Type</th>
                                        <th>Sent to</th>
                                        <th>Success</th>
                                        <th>Failed</th>
                                        <th>Acknowledge</th>
                                        <th>Sent Date</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                        <tr>
                                            <td style="text-align: center;"><?=$sr++;?></td>
                                            <td>HRMSNOTI0<?=$row->id;?></td>
                                            <td><?php if($row->notication_type==1){ echo 'Office Wise';}elseif($row->notication_type==2){ echo 'Department Wise';}elseif($row->notication_type==3){ echo 'Designation Wise';}elseif($row->notication_type==4){ echo 'Specific Employees';}elseif($row->notication_type==5){ echo 'Group Wise';}elseif($row->notication_type==6){ echo 'Title Wise';}elseif($row->notication_type==7){ echo 'Client visit allowed';}?>
                                                
                                            </td>
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
                                                }    ?>
                                            </td>
                                            <td>
                                <?php
                                    $suc_sent = \DB::table('push_notification_histories')->where('notification_id', $row->id)->where('status', 1)->count();
                                    echo $suc_sent;
                                ?>
                                            </td>
                                            <td><?php
                                    $fail_sent = \DB::table('push_notification_histories')->where('notification_id', $row->id)->where('status', 0)->count();
                                    echo $fail_sent;
                                ?></td>
                                                </td>
                                            <td><?php
                                    $ack_sent = \DB::table('push_notification_histories')->where('notification_id', $row->id)->where('is_view', 1)->count();
                                    echo $ack_sent;
                                ?></td>
                                                <td><?php
                                    $sent_date = \DB::table('push_notification_histories')->where('notification_id', $row->id)->select('created_at')->first();
                                    if($sent_date){
                                    echo date('d M, Y H:i:s', strtotime(@$sent_date->created_at));
                                    }
                                ?></td>
                                            <td style="white-space: nowrap;">
                                                <p style="max-width:200px;white-space: normal;padding: 0px;margin: 0px;"><b>Title</b>:<?=$row->title;?></p>
                                                <p style="max-width:200px;white-space: normal;padding: 0px;margin: 0px;"><b>Description</b>:<?=$row->description;?></p>
                                                <b>Image</b>:
                                                <?php if(!empty($row->image)){?>
                                                    <a target="_blank" href="{!! asset($row->image) !!}" style="color:blue"> Click here</a>
                                                <?php } else {
                                                    echo 'N/A';
                                                }?>
                                            </td>
                                            <td>
                                                <a style="background: #1877f2; border-color: #1877f2;" class="text-white btn-sm status_checks btn btn-primary" href="{{ route('notification-reports', $row->id) }}">Download Report &nbsp;<i class="fa fa-cloud-download" style="font-size:22px" aria-hidden="true"></i><a/>
                                            </td>
                                        </tr>
                                        <div id="myModal<?=$row->id;?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Branch Name</h4>
                                                </div>
                                                <div class="modal-body">
                                                <?php if($row->notication_type==1 && !empty($row->branch_id)){
                                                    $trmin=rtrim($row->branch_id,',');
                                                    $branch=$this->db->query("SELECT name FROM `default_cio_locations` WHERE id IN ($trmin)")->result();
                                                    foreach($branch as $bran){
                                                        echo '<span  class="btn show-amazing" style="display: flex;">'.$bran->name.'</span>';
                                                    }
                                                } ?>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>
                                </tbody>
                            </table>
                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection