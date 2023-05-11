@extends('layouts.user.app')
@section('content')
<style>
    .label-info{
        background-color: #5cb85c;
    }
    .label-danger{
        background-color: red;
    }
    .show-amazing{
        background: #ffffff;
        border-color: #bcb1b1;
        font-size: 12px;
        color: #000000!important;
        padding: 1px 4px;
        text-align: left;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-9 col-6">
                                <h5>Candidate Hiring Request</h5>
                            </div>
                            <div class="col-md-3 col-6">
                                <form action="{{url('candidate-hiring-request')}}" method="GET" class="float-right w-100">
                                    <select class="form-control" name="check_status" onchange="this.form.submit()">
                                        <option value="Pending" <?php if(!empty($_GET['check_status'])){ if($_GET['check_status']=='Pending'){ echo 'selected';}} ?>>Pending</option>
                                        <option value="Approved" <?php if(!empty($_GET['check_status'])){ if($_GET['check_status']=='Approved'){ echo 'selected';}} ?>>Approved</option>
                                        <option value="Rejected" <?php if(!empty($_GET['check_status'])){ if($_GET['check_status']=='Rejected'){ echo 'selected';}} ?>>Rejected</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status Type Name</th>
                                    <th>Candidate Name</th>
                                    <th>Document Download</th>
                                    <th><?php 
                                    if(!empty($_GET['check_status'])){
                                        if($_GET['check_status']=='Approved'){
                                            echo 'Approved By & Updated On';
                                        }elseif($_GET['check_status']=='Rejected'){
                                            echo 'Rejected By & Updated On';
                                        }else{
                                            echo 'Status';
                                        }
                                    }else{
                                        echo 'Status';
                                    }?></th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <body>
                                @if(!empty($rowdata))
                                    @foreach($rowdata as $rows)
                                    @php $user_id = Auth::user()->organisation_id; @endphp
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->status_name}}</td>
                                            <td>{{$rows->salutation}} {{$rows->first_name}} {{$rows->middle_name}} {{$rows->last_name}}</td>
                                            <td>
                                            <?php
                                                $doc = App\Models\InterviewDocument::select('documnet_title','documnet_file')->where('hiring_approvals_id',$rows->id)->get();
                                                if(!empty($doc)){
                                                    foreach($doc as $doc){ 
                                                    echo '<a href="'.url("public/uploads/status_document").'/'.$doc->documnet_file.'" download><span class="btn show-amazing bg-warning" style="padding: 5px 8px;color:white">'.$doc->documnet_title.'&nbsp; &nbsp; <i class="fa fa-file text-white" aria-hidden="true"></i></span></a>';
                                                } }
                                            ?>
                                            </td>
                                            <td>
                                                <?php
                                                if(!empty($_GET['check_status'])){ 
                                                    if($_GET['check_status']=='Pending'){
                                                        echo '<a href="#" data-toggle="modal" data-target="#myModal" onclick="show_data('.$rows->id.')"><span class="btn bg-danger" style="padding: 5px 8px;color:white">Pending</span></a>';
                                                    }else{
                                                        if(!empty($rows->approved_by)){
                                                            $emp1 = DB::select("SELECT b.id,a.employee_code,b.name FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code is NOT null AND a.organisation_id=$user_id AND b.id=$rows->approved_by LIMIT 1");
                                                            if(!empty($emp1)){
                                                                foreach($emp1 as $emps){ 
                                                                echo '<span class="btn show-amazing">'.$emps->employee_code.' - '.$emps->name.' <br/> Updated On '.date_format(date_create($rows->updated_at),"d-M-Y H:i").'</span>';
                                                            } }
                                                        }
                                                    }
                                                }else{
                                                    if(!empty($rows->approved_by)){
                                                        $emp1 = DB::select("SELECT b.id,a.employee_code,b.name FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code is NOT null AND a.organisation_id=$user_id AND b.id=$rows->approved_by LIMIT 1");
                                                        if(!empty($emp1)){
                                                            foreach($emp1 as $emps){ 
                                                            echo '<span class="btn show-amazing">'.$emps->employee_code.' - '.$emps->name.' <br/> Updated On '.date_format(date_create($rows->updated_at),"d-M-Y H:i").'</span>';
                                                        } }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>{{date_format(date_create($rows->created_at),"d-M-Y H:i")}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </body>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Update Candidate Hiring Request</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
                <form class="forms-sample row" action="{{url('update-hiring-status')}}" method="POST">
                    @csrf
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Select Status</label>
                            <input type="hidden" name="hiring_id" id="hiring_id" class="form-control">
                            <select name="status" class="form-control">
                                <option value="">--Select--</option>
                                <option value="1"> Approve </option>
                                <option value="2"> Reject </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status Remark</label>
                            <textarea name="status_remark" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function show_data(id){
    $('#hiring_id').val(id);
}
$(document).ready(function () {
    var datatable = $('#examples').dataTable({
    dom: 'Bfrtip',
    buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    });
});
</script>
@endsection('content')