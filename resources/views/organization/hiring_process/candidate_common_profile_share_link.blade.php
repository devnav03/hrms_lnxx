@extends('layouts.organization.app')
@section('content')
<style>
    .lable-danger{
        background-color: #d9534f;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    .lable-success{
        background-color: #5cb85c;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    #candidate_data td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    @media (min-width: 992px){
        .modal-lg, .modal-xl {
            max-width: 1000px;
        }
    }
    .dropdown .dropdown-menu{
        box-shadow: 0px 1px 15px 1px rgb(0 0 0 / 35%);
    }

    .table td img {
      width: 350px!important; 
      height: 250px!important; 
      border-radius: 0%!important; blue 2px solid;
      border-color: ; 
}

</style>





<div class="main-panel">
    <div class="content-wrapper">
         <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Candidate Profile Details</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                         <table class="table table-condensed">
                            <tr><td>
                                
                                <table class="table table-condensed">
                            <thead>
                            <tr style="background-color:#eeeeee!important;"><td colspan="8">Basic Details</td></tr>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Manager Name</th>
                                    <th>Gender</th>
                                    <th>Candidate Email</th>
                                    <th>HR Email</th> 
                                    <th>Request Date</th>   
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->candidate_name}} </td>
                                    <td>{{$row->position_name}} </td>
                                    <td>{{$row->manager_name}}</td>
                                    <td>{{ucwords($row->candidate_gender)}} </td>
                                    <td>{{$row->candidate_email}}</td>
                                    <td>{{$row->hr_email}}</td>        
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                            </td></tr>
                            <tr><td>
                               <table class="table table-condensed">
                        <thead>
                            <tr style="background-color:#eeeeee!important;">
                                <td colspan="3" style="text-align: center;"><h4>Candidate Uploaded Required Docs</h4></td>
                            </tr>
                            <tr>
                                    <td><b>Document Title</b></td>
                                    <td><b>Document Details</b></td>
                                    <td><b>Action</b></td>
                                </tr>
                        <tr>
                           <?php if(!empty($getRequiredDoc)){ 
                            ?>
                                <table class="table table-condensed">
                               <?php  foreach($getRequiredDoc as $requiredDoc){?>
                                <tr>
                                    <td><?php echo $requiredDoc->document_title;?></td>
                                    <td>
                                    <img src="{{asset('uploads/candidate-upload-required-doc/')}}/<?php echo $requiredDoc->document_file;?>" height="350 !important;" width="500 !important;">
                                        <?php //echo $requiredDoc->document_file;?> </td>
                                    <td><i class="fa fa-eye"></i></td>
                                </tr>
                               <?php } ?>
                                </table>
                                <?php } ?> 
                                </tr>
                                </thead>    
                                </table>
                            </td></tr>
                            <tr><td><table class="table table-condensed">
                                <thead>
                                <tr style="background-color:#eeeeee!important;">
                                <td colspan="3" style="text-align: center!important;"><h4>Candidate Signed Uploaded Docs</h4></td>
                                </tr>
                                <tr>
                                <tr>
                                    <td><b>Document Title</b></td>
                                    <td><b>Document Details</b></td>
                                    <td><b>Action</b></td>
                                </tr>
                                <?php if(!empty($getSignedDoc)){ 
                                ?>
                                <table class="table table-condensed">
                                <?php  foreach($getSignedDoc as $signedDoc){?>
                                <tr>
                                <td><?php echo ($signedDoc->document_title);?></td>
                                <td>
                            <img src="{{asset('uploads/candidate-upload-signed-doc/')}}/<?php echo $signedDoc->document_file;?>" height="350 !important;" width="500 !important;">
                               </td>
                                <td><i class="fa fa-eye"></i></td>
                                </tr>
                                <?php } ?>
                                </table>
                                <?php } ?> 
                                </tr>
                                </thead>    
                            </table>
                            </td></tr>
                            <tr><td>
                                 <?php if(!empty($getVisaApprovalStatus)) { ?>
                            <table class="table table-condensed">
                                <thead>
                                <tr style="background-color:#EEEEEE!important;">
                                <td colspan="3" style="text-align: center;"><h4>Candidate eVisa Status</h4></td>
                                </tr>
                                <tr>
                                <tr>
                                    <td><b>e-Visa Status</b></td>
                                    <td><b>Accepted Date</b></td>
                                    <td><b>Rejected Date</b></td>
                                </tr>
                                <?php if(!empty($getSignedDoc)){ 
                                ?>
                                <table class="table table-condensed">
                              
                        <tr>
                        <td><?php if($getVisaApprovalStatus->visa_approved_reject_status=='1') { echo 'Accepted'; }elseif ($getVisaApprovalStatus->visa_approved_reject_status=='2') { echo 'Rejected'; } else{
                            echo 'NA'; } ?>
                        </td>
                        <td><?php if($getVisaApprovalStatus->visa_approved_date!=null) {
                            echo $getVisaApprovalStatus->visa_approved_date;} else { echo 'NA'; } ?> </td>
                        <td><?php if($getVisaApprovalStatus->visa_rejected_date!=null) {
                            echo $getVisaApprovalStatus->visa_rejected_date;} else { echo 'NA'; } ?> </td>
                        </tr>
                              
                                </table>
                                <?php } ?> 
                                </tr>
                                </thead>    
                            </table>
                          <?php } ?>  

                            </td></tr>




                             <tr><td>
                                
                                &nbsp;
                            </td></tr>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

 

 
 
 
 
 

@endsection('content')