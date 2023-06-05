<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organisation;
use App\Models\City;
use App\Models\EmpAttendance;
use App\Models\Leave;
use App\Models\ProjectActivity;
use App\Models\Timeseet;
use App\Models\EmpDetail;
use App\Models\SourceMaster;
use App\Models\PositionMaster;
use App\Models\NoticeMaster;
use App\Models\EducationMaster;
use App\Models\EmpDocument;
use App\Models\LetterTemplate;
use App\Models\ProjectMaster;
use App\Models\FormEngineCategory;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\ShiftMaster;
use App\Models\HeaderFooterMaster;
use App\Models\HeaderFooterTemplateMaster;
use App\Models\LeaveType;
use App\Models\EmpType;
use App\Models\State;
use App\Models\EmployeeInfo;
use App\Models\AssignTask;
use App\Models\FormEngine;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\NotificationTemplate;
use App\Models\WeekDay;
use App\Models\TemplateMaster;
use App\Models\ResourceRequirement;
use App\Models\LeaveAuthority;
use App\Models\FlowMaster;
use App\Models\ApprovalFlow;
use App\Models\InterviewHistory;
use App\Models\NotificationSetting;
use App\Models\InterviewDocument;
use App\Models\HiringApproval;
use App\Models\InterviewHiringStatu;
use App\Models\SendHrRequest;
use App\Models\OfferLetter;
use App\Models\SendOfferLettersToCandidate;
use App\Models\SendVisaApproval;
use App\Models\CandidateRequiredDocument;
use App\Models\CandidateSignedDocument;
use App\Models\DocumentMaster;
use App\Models\SendProLcMol;
use App\Models\LcMolDoc;
use App\Models\SignedLcMolDoc;
use App\Models\SendProEvisaProcessing;
use App\Models\VisaDocument;
use App\Models\MedicalAppointment;
use App\Models\MedicalReport;
use App\Models\EidProcesse;
use App\Models\EidDocument;
use App\Models\Vander;
use App\Models\VanderStaff;
use Illuminate\Support\Str;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    //public $from_email = "vikaspyadava@gmail.com";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
   #############################START VIKAS NEW CODES#####################################     
    public function GetCandidateFullProfile($id){  
    $user_id= Auth::user()->id;
    $results=DB::select("SELECT a.id,a.candidate_name,b.position_name,a.candidate_position_id,a.candidate_email,a.candidate_mobile,a.manager_name,a.candidate_salary,a.candidate_gender,a.hr_email,a.hiring_status,a.created_at FROM `send_hr_requests` as a INNER JOIN `position_masters` as b ON a.candidate_position_id=b.id WHERE a.organisation_id=$user_id AND a.id=$id"); 
    return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$results]);
}


public function GetDocuments(Request $request){ 
        $organisation_id = Auth::user()->id;
        $candidate_id=$request->candidate_id;
        $document_id=$request->document_id;
        $document_data = CandidateRequiredDocument::select('id','document_title','document_file')->where('organisation_id',$organisation_id)->where('candidate_id',$candidate_id)->where('document_id',$document_id)->count();

        if($document_data=='0'){
            $data="";
        } else {
             $data="Document already uploaded ! Please select other docs.";
        }

        if(empty($data)){
        return response()->json(['status'=>200,'data' => []]);
        }else{
        return response()->json(['status'=>400,'data' =>$data]);
        }   
}

public function GetScannedDocuments(Request $request){ 
        $organisation_id = Auth::user()->id;
        $candidate_id=$request->candidate_id;
        $document_id=$request->document_id;
        $document_data = CandidateSignedDocument::select('id','document_title','document_file')->where('organisation_id',$organisation_id)->where('candidate_id',$candidate_id)->where('document_id',$document_id)->count();

        if($document_data=='0'){
            $data="";
        }else{
             $data="Document already uploaded ! Please select other docs.";
        }

        if(empty($data)){
        return response()->json(['status'=>200,'data' => []]);
        }else{
        return response()->json(['status'=>400,'data' =>$data]);
        }   
}



public function GetCandidateName(Request $request){ 
        $organisation_id = Auth::user()->id;
        $candidate_id=$request->candidate_id;

        $data = SendHrRequest::select('id','candidate_name')->where('organisation_id',$organisation_id)->where('id',$candidate_id)->first();
        if(!empty($data)){
        return response()->json(['status'=>200,'data' =>$data]);
        }else{
        return response()->json(['status'=>400,'data' =>[]]);
        }   
}


/*------------START GET CANDIDATE COMPLETE DETAILS--------------*/

public function GetCandidateAllHiringDetails(Request $request){
        $candidate_id = $request->segment(3);
        $user_id = Auth::user()->id;
        $result=DB::select("SELECT a.id,a.organisation_id,a.candidate_name,b.position_name,a.candidate_email,a.candidate_salary,a.candidate_gender,a.manager_name,a.candidate_mobile,a.hiring_status,a.hr_email,a.candidate_resume,a.created_at FROM `send_hr_requests` as a INNER JOIN `position_masters` as b on a.candidate_position_id=b.id WHERE a.organisation_id=$user_id AND a.id=$candidate_id ORDER BY a.id ASC");
        $offer_letter="";
        $getRequiredDoc=CandidateRequiredDocument::select('id','candidate_id','document_title','document_file','created_at','updated_at','tracking_status','doc_upload_date','status', 'document_id', 'organisation_id', 'created_by')->where('organisation_id',$user_id)->where('candidate_id',$candidate_id)->orderBy('id', 'desc')->get();
        // echo "<pre>"; print_r($getRequiredDoc); echo "</pre>"; die;

        $getSignedDoc=CandidateSignedDocument::select('id','candidate_id','document_title','document_file','created_at','updated_at','tracking_status','doc_upload_date','status')->where('organisation_id',$user_id)->where('candidate_id',$candidate_id)->get();

        $getVisaApprovalStatus=SendVisaApproval::select('id','candidate_id','visa_approved_reject_status','visa_approved_date','visa_rejected_date')->where('organisation_id',$user_id)->where('candidate_id',$candidate_id)->first();
        //echo "<pre>"; print_r($getVisaApprovalStatus); echo "</pre>"; die;
        ?>

     <!--    <style>.topics tr { line-height: 14px; background-color:#eeeeee!important; }</style> -->

            <div class="col-md-12 mb-3"><h4 style="text-align:center;">Complete Candidate Details</h4>
                <div class="card mb-4 mb-md-0">
                    <div class="card-body">
                        <h5 class="mb-2"> Candidate Basic Info </h5>
                        <div class="row">
                    <table class="table table-condensed">
                    <thead>
                    <tr>
                       <?php if(!empty($result)){ 
                        foreach($result as $results_data){
                        ?>
                            <table class="table table-condensed">
                            <tr>
                                <td>Candidate Name</td>
                                <td style="border-right: 1px solid #f3f3f3;"><?php echo $results_data->candidate_name;?> </td>   
                                <td>Candidate Position</td>
                                <td><?php echo $results_data->position_name;?> </td>
                            </tr>
                            <tr> 
                                <td>Candidate Email</td>
                                <td style="border-right: 1px solid #f3f3f3;"><?php echo $results_data->candidate_email;?> </td>
                                <td>Candidate Gender</td>
                                <td><?php echo $results_data->candidate_gender;?> </td>
                            </tr>
                            <tr> 
                                <td>Candidate Salary</td>
                                <td style="border-right: 1px solid #f3f3f3;"><?php echo $results_data->candidate_salary;?> </td>
                                <td>Candidate Mobile No.</td>
                                <td><?php echo $results_data->candidate_mobile;?> </td>
                            </tr>
                            <tr> 
                                <td>Manager Name</td>
                                <td style="border-right: 1px solid #f3f3f3;"><?php echo $results_data->manager_name;?> </td>
                                <td>HR Email</td>
                                <td><?php echo $results_data->hr_email;?> </td>
                            </tr>
                            </table>
                            <?php } } ?> 
                            </tr>
                            </thead>    
                            </table>
                        </div>


                        <div class="row">
                    <table class="table table-condensed">
           
                        <tr style="background-color:#eeeeee!important;">
                            <td colspan="6" style="text-align: left;"><h4>Uploaded Document</h4></td>
                        </tr>
                        <tr>
                            <td><b>S. No</b></td>
                            <td><b>Document Type</b></td>
                            <td><b>Title</b></td>
                            <td><b>File</b></td>
                            <td><b>Uploaded By</b></td>
                            <td><b>Uploaded At</b></td>
                        </tr>

                        <?php  $i = 0; 

                        $LcMolDocs = MedicalReport::where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>
                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>Medical Test Report</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="uploads/medical_test/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>
                            <?php  
                            $user_name = User::where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                        </tr>

                        <?php
                        }
                    }

                        $LcMolDocs = VisaDocument::where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>

                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>eVisa Document</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="uploads/upload_lc_mol_doc/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>

                            <?php  
                            $user_name = User::where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                        
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                            </tr>

                        <?php
                        }
                    }
                        $LcMolDocs = SignedLcMolDoc::where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>

                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>LC/MOL Signed Copy</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="uploads/upload_lc_mol_doc/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>


                            <?php  
                            if($LcMolDoc->created_by == 'candidate'){ ?>
                            <td>Candidate</td>
                            <?php } else {

                            $user_name = User::where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <?php } ?>
                        
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                            </tr>

                        <?php
                        }
                    }

                        $LcMolDocs = LcMolDoc::where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>
        
                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>LC/MOL Document</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="uploads/upload_lc_mol_doc/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>
                            <?php  
                            $user_name = User::where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                        </tr>

                       <?php
                        }
                        }
                        if(!empty($getRequiredDoc)){ 
                        ?>
                            <?php foreach($getRequiredDoc as $requiredDoc){
                            $i++;
                                ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                            <?php 
                            $doc = DocumentMaster::where('id', $requiredDoc->document_id)->select('document_title')->first();
                            ?>     
                                <td><?php echo $doc->document_title; ?></td>
                                <td><?php echo $requiredDoc->document_title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="uploads/candidate-upload-required-doc/<?php echo $requiredDoc->document_file;?>" target="_blank">View</a></td>

                            <?php if($requiredDoc->created_by == 'HR'){ 
                            $user_name = User::where('id', $requiredDoc->organisation_id)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <?php } else {  ?>   
                            <td> Candidate </td>
                            <?php } ?>
                            <td> <?php echo date('d M, Y', strtotime($requiredDoc->doc_upload_date)); ?> </td>
                            </tr>
                           <?php } ?>
                     
                            <?php } ?> 
                     

                           <?php 
                            $results_data = SendOfferLettersToCandidate::where('candidate_id', $candidate_id)->select('organisation_id', 'document_title', 'document_file', 'created_at')->first();
                           if(!empty($results_data->organisation_id)) {
                            
                            $document_title = json_decode($results_data->document_title);
                            $document_file = json_decode($results_data->document_file);
                            foreach ($document_title as $key => $value) {
                                $i++;
                            ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td>Offer Letter</td>
                            <td><?php echo $value; ?></td>
                            <td>
                            <?php
                            foreach ($document_file as $key1 => $value1) {
                                if($key1 == $key) {
                            ?>    
                            <a target="_blank" class="btn btn-primary btn-xs" href="uploads/upload_offer_letter_document/<?php echo $value1; ?>">View</a>
                            <?php } } ?>
                        </td>

                          <?php  
                            $user_name = User::where('id', $results_data->organisation_id)->select('name')->first();
                            ?>
                           <td><?php echo $user_name->name; ?></td>
                           <td><?php echo date('d M, Y', strtotime($results_data->created_at)); ?></td>
                        </tr>
                       
                        <?php }  } ?>
                        
                        <?php 

                        $results_data = SendHrRequest::where('id', $candidate_id)->select('candidate_resume', 'manager_name', 'created_at')->first();

                           if(!empty($results_data->candidate_resume)) {
                             $i++;
                            ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td>Resume</td>
                            <td></td>
                            <td>
                            <a target="_blank" class="btn btn-primary btn-xs" href="<?php echo $results_data->candidate_resume;?>" download>View</a></td>
                           <td><?php echo $results_data->manager_name; ?></td>
                           <td><?php echo date('d M, Y', strtotime($results_data->created_at)); ?></td>
        
                            </tr>
                        <?php } ?>

                        </table>

                        <table class="table table-condensed">
                            <tr style="background-color:#eeeeee!important;">
                            <td colspan="5" style="text-align: left;"><h4>Status History</h4></td>
                            </tr>
                            <tr>
                                <td><b>S No.</b></td>
                                <td><b>Process</b></td>
                                <td><b>Status</b></td>
                                <td><b>By</b></td>
                                <td><b>Date</b></td>
                            </tr>

                    <?php 
                    $j = 1;
                    $SendProLcMol = EidDocument::where('candidate_id', $candidate_id)->select('created_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>EID Document</td>
                            <td>Uploaded</td>
                        <?php 
                     
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>

                    <?php 
                    }
                    ?>

                    <?php 
                    $SendProLcMol = EidProcesse::where('candidate_id', $candidate_id)->select('request_send_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>EID Process</td>
                            <td>Sent To PRO</td>
                        <?php 
                     
                        $user_name = User::where('id', $SendProLcMol->request_send_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                    <?php 
                    $SendProLcMol = MedicalReport::where('candidate_id', $candidate_id)->select('created_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Medical Reports</td>
                            <td>Uploaded</td>
                        <?php 
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                     <?php 
                    $SendProLcMol = MedicalAppointment::where('candidate_id', $candidate_id)->select('created_by', 'created_at', 'place', 'appointment_time')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Medical Test Appointment<br>
                            Date: <?php echo date('d M, Y, H:i', strtotime($SendProLcMol->appointment_time)); ?> <br> Place: <?php echo $SendProLcMol->place;?></td>
                            <td>Sent</td>
                        <?php 
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                    <?php 
                    $SendProLcMol = VisaDocument::where('candidate_id', $candidate_id)->select('created_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>eVisa Documents</td>
                            <td>Uploaded</td>
                        <?php 
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                    <?php 
                    $SendProLcMol = SendProEvisaProcessing::where('candidate_id', $candidate_id)->select('created_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Sent PRO to eVisa Processing</td>
                            <td>Sent</td>
                        <?php 
                     
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }  
                    ?>

                    <?php
                    $SendProLcMol = SignedLcMolDoc::where('candidate_id', $candidate_id)->select('created_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?> 
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>LC/MOL signed copy</td>
                            <td>Uploaded</td>
                        <?php 
                        if($SendProLcMol->created_by == 'candidate'){ ?>
                            <td>Candidate</td>
                        <?php } else {
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                        <?php } ?>
                        <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                    <?php 
                    $SendProLcMol = LcMolDoc::where('candidate_id', $candidate_id)->select('created_by', 'created_at')->orderBy('id', 'desc')->first();
                    if(!empty($SendProLcMol)) {
                    ?>
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>LC/MOL documents</td>
                            <td>Uploaded</td>
                        <?php 
                        $user_name = User::where('id', $SendProLcMol->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    } 
                    ?>

                    <?php
                    $SendProLcMol = SendProLcMol::where('candidate_id', $candidate_id)->select('request_send_by', 'created_at')->first();
                    if(!empty($SendProLcMol)) {
                    ?>
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Sent for LC/MOL process</td>
                            <td>Sent</td>
                        <?php 
                        $user_name = User::where('id', $SendProLcMol->request_send_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendProLcMol->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                    <?php 
                    $SendVisaApproval = SendVisaApproval::where('candidate_id', $candidate_id)->where('visa_approved_reject_status', '!=', 0)->select('manager_id', 'visa_approved_date', 'visa_rejected_date', 'visa_approved_reject_status')->first();
                    if(!empty($SendVisaApproval)) {
                    ?>
                    <tr>
                            <td><?php echo $j++; ?></td>    
                            <td>eVisa</td>
                        <?php
                            if($SendVisaApproval->visa_approved_reject_status == 1){
                        ?>
                            <td>Approved</td>
                        <?php } else { ?>
                            <td>Rejected</td>
                        <?php } ?> 
                        <?php 
                        $user_name = User::where('id', $SendVisaApproval->manager_id)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <?php
                            if($SendVisaApproval->visa_approved_reject_status == 1) {
                        ?>
                            <td><?php echo date('d M, Y', strtotime($SendVisaApproval->visa_approved_date)); ?></td>
                        <?php } else { ?>
                            <td><?php echo date('d M, Y', strtotime($SendVisaApproval->visa_rejected_date)); ?></td>
                        <?php } ?> 
                    </tr>
                    <?php
                    }
                    ?>

                    <?php
                    $SendVisaApproval = SendVisaApproval::where('candidate_id', $candidate_id)->select('organisation_id', 'created_at')->first();
                    if(!empty($SendVisaApproval)) {
                    ?>
                    <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Sent for eVisa approvals</td>
                            <td>Sent</td>
                        <?php 
                        $user_name = User::where('id', $SendVisaApproval->organisation_id)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td><?php echo date('d M, Y', strtotime($SendVisaApproval->created_at)); ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                    <?php
                        $sign_doc = CandidateRequiredDocument::where('candidate_id', $candidate_id)->where('document_id', 6)->select('doc_upload_date', 'created_by', 'organisation_id')->first();
                        if(!empty($sign_doc)){    
                        ?>
                        <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Offer Letter Signed Copy</td>
                            <td>Uploaded</td>
                        <?php 
                        if($sign_doc->created_by == 'HR') {
                        $user_name = User::where('id', $sign_doc->organisation_id)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>

                        <?php } else { ?>
                            <td>Candidate</td>
                        <?php } ?>
                        <td><?php echo date('d M, Y', strtotime($sign_doc->doc_upload_date)); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                            $offer_letter_status = SendOfferLettersToCandidate::where('candidate_id', $candidate_id)->select('status', 'updated_at')->first();
                        if($offer_letter_status){    
                        if($offer_letter_status->status != 0) {
                        ?>
                        <tr>
                            <td><?php echo $j++; ?></td>
                            <td>Offer Letter</td>
                            <td><?php if($offer_letter_status->status == 1){  ?> Accepted
                            <?php } else { ?> Rejected <?php } ?></td>
                            <td>Candidate</td>
                            <td><?php echo date('d M, Y', strtotime($offer_letter_status->updated_at)); ?></td>
                        </tr>
                    <?php
                        }
                    }
                    ?> 
                        </table> 
                        </div>
                    </div>
                </div>
            </div>
        <?php 
 }

    public function UploadOfferLetterDocument(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
    $result = SendHrRequest::where(['id'=>$candidate_id,'hiring_status'=>'0'])->first();
    $input=$request->all();
    $images=array();
    if(!empty($result)){
    if($files=$request->file('upload_document')){ 
    $document_title=array();
    $document_file=array();
    $sr=0;
    $date = date('Y-m-d H:i:s');
    $attach_ments = [];
    foreach($files as $file){
        $newfilename = rand(100000, 999999);
        $name=$file->getClientOriginalName();
        $new_names='Ol_'.$newfilename.'_'.$name;

        $file->move('public/uploads/upload_offer_letter_document',$new_names);
        $attach_ments[] = public_path('uploads/upload_offer_letter_document/' . $new_names);

        $saveofferRec = new SendOfferLettersToCandidate();
        $document_title[] = $request->filename[$sr++];
        $document_file[] =  $new_names;     
    }

        $get_duplicate = SendOfferLettersToCandidate::where(['organisation_id'=>$user_id,'candidate_id'=>$candidate_id])->first(); 
         if(empty($get_duplicate)){                  
            $saveofferCandiateRec = new SendOfferLettersToCandidate();
            $saveofferCandiateRec->organisation_id = $user_id;
            $saveofferCandiateRec->candidate_id = $result->id;
            $saveofferCandiateRec->name = $result->candidate_name;
            $saveofferCandiateRec->email = $result->candidate_email;
            $saveofferCandiateRec->position = $result->candidate_position_id;
            $saveofferCandiateRec->salary = $result->candidate_salary;
            $saveofferCandiateRec->gender = $result->candidate_gender;
            $saveofferCandiateRec->manager_name = $result->manager_name;
            $saveofferCandiateRec->mobile = $result->candidate_mobile;
            $saveofferCandiateRec->document_title= json_encode($document_title); 
            $saveofferCandiateRec->document_file = json_encode($document_file);
            $saveofferCandiateRec->tracking_status  =  '1';
            $saveofferCandiateRec->offer_letter_release_date = $date;
            $data=$saveofferCandiateRec->save(); 
            $msg="Offer Letter Send Successfully.";

            $results = DB::table('send_hr_requests')
            ->where(['id'=>$result->id])
            ->update(['hiring_status' => '1',]);
          } else {
            $msg="Offer Letter Already Send.";
            $data='';
          }         
    }

    }  

    if(!empty($data)){
        $updated_rec = SendOfferLettersToCandidate::where(['candidate_id'=>$candidate_id,'tracking_status'=>'1'])->first();
         if(!empty($updated_rec)){
                $this->SendHrOfferLetterMail($updated_rec, $updated_rec->id, $attach_ments);
        }
        return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);
       
    }else{  $msg="Offer Letter Already Send.";
    return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
    }

}



/*-------------------END UPLOAD OFFER LETTER DOCUMENT------------------*/

// public function SendHrOfferLetterMail($data){
//     $user_id = Auth::user()->id;
//     $email = array($data->email);
//     $rec_id=$data->id;
//     $organisation_id=$data->organisation_id;
//     $candidate_id=$data->candidate_id;

//     $positions = PositionMaster::select('position_name')->where(['orgnization_id'=>$user_id,'id'=>$data->id])->first();
//     try {
//     $template_data = [
//         'name'           => $data->name,
//         'email'          => $data->email,
//         'mobile'         => $data->mobile,
//         'position'       => $positions->position_name,
//         'document_title' => $data->document_title,
//         'document_file'  => json_encode($data->document_file),
//         'manager_name'   =>  json_encode($data->manager_name),
//     ];

//      Mail::send(['html'=>'email.offer_letter'], $template_data,
//         function ($message) use ($email,$template_data) {
//             $message->to($email)->from("lnxx@gmail.com")->subject($template_data['name'].' '.$template_data['manager_name']);
//     }); 
//     return true;
//     } catch (Exception $ex) {
//     return false;
//     }  
// }


/*-------------UPLOAD REQUIRED DOC---------------*/


public function UploadMedicalReportsSendCandidate(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
    $result_ = MedicalAppointment::where('candidate_id', $candidate_id)->first();
    if(!empty($result_)){
    $result = SendHrRequest::where('id', $candidate_id)->first();
  //  dd($request);
    $data = '';
    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    $sr = 0;
    $user_id = Auth::user()->id;
    $date = date('Y-m-d H:i:s');
    $files= $request->file('upload_document');
    $attach_ments = [];
    foreach($files as $key => $file){
        //dd($file);
        $newname= rand(100000, 999999);
        $name= $file->getClientOriginalName();
        $name = preg_replace('!\s+!', '-', $name);
        
        $newuploadedfilename='eV_'.$newname.'_'.$name;
        $file->move('public/uploads/medical_test',$newuploadedfilename);
        $document_title = $request->filename[$key];
        $document_file = $newuploadedfilename;  

        $attach_ments[] = public_path('uploads/medical_test/' . $newuploadedfilename);
      
        $uploadRequredDoc = new MedicalReport();
        $uploadRequredDoc->candidate_id=$candidate_id; 
        $uploadRequredDoc->title= $document_title; 
        $uploadRequredDoc->file_name = $document_file;
        $uploadRequredDoc->created_by = $user_id; 
        $data = $uploadRequredDoc->save();

    }

    $result1 = MedicalAppointment::where('candidate_id', $candidate_id)->first();
    $result1->status = 1;
    $result1->save();

    }  

    if(!empty($data)){   

    $template_data = [
        'name' => $result->candidate_name,
    ]; 

    $email = $result->candidate_email;
 
    Mail::send(['html'=>'email.medical_reports'], $template_data, function ($message) use ($email, $template_data, $document_file, $document_title, $attach_ments) {
            $message->to($email)
            ->from("lnxx@gmail.com")
            ->subject('Medical Reports');
        foreach ($attach_ments as $file) {
           $message->attach($file);
        }
    });

    return response()->json(['status'=>200,'msg'=>'Medical Reports Uploaded Successfully','data' => $data]);       
    }else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later']);
    }
} else {

    return response()->json(['status'=>400,'msg'=>'First need to send medical appointment date']);
}
}


public function UploadEidSendCandidate(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
    $result_ = EidProcesse::where('candidate_id', $candidate_id)->first();
    if(!empty($result_)){
    $result = SendHrRequest::where('id', $candidate_id)->first();
  //  dd($request);
    $data = '';
    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    $sr=0;
    $user_id = Auth::user()->id;
    $date = date('Y-m-d H:i:s');
    $files= $request->file('upload_document');
    $attach_ments = [];
    foreach($files as $key => $file){
        //dd($file);
        $newname= rand(100000, 999999);
        $name= $file->getClientOriginalName();
        $name = preg_replace('!\s+!', '-', $name);
        
        $newuploadedfilename='eV_'.$newname.'_'.$name;
        $file->move('public/uploads/upload_lc_mol_doc',$newuploadedfilename);
        $document_title = $request->filename[$key];
        $document_file = $newuploadedfilename;  

        $attach_ments[] = public_path('uploads/upload_lc_mol_doc/' . $newuploadedfilename);
      
        $uploadRequredDoc = new EidDocument();
        $uploadRequredDoc->candidate_id=$candidate_id; 
        $uploadRequredDoc->title= $document_title; 
        $uploadRequredDoc->file_name = $document_file;
        $uploadRequredDoc->created_by = $user_id; 
        $data = $uploadRequredDoc->save();

    }

    $result1 = SendHrRequest::where('id', $candidate_id)->first();
    $result1->hiring_status = 12;
    $result1->save();

    }  

    if(!empty($data)){   

    $template_data = [
        'name' => $result->candidate_name,
    ]; 

    $email = $result->candidate_email;
 
    Mail::send(['html'=>'email.eid_doc'], $template_data, function ($message) use ($email, $template_data, $document_file, $document_title, $attach_ments) {
            $message->to($email)
            ->from("lnxx@gmail.com")
            ->subject('E-ID Documents');
        foreach ($attach_ments as $file) {
           $message->attach($file);
        }
    });

    return response()->json(['status'=>200,'msg'=>'Document Uploaded Successfully','data' => $data]);       
    } else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later']);
    }
} else {

    return response()->json(['status'=>400,'msg'=>'First need to send PRO for E-ID process.']);
}
}


public function UploadeVisaSendCandidate(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
    $result_ = SendProEvisaProcessing::where('candidate_id', $candidate_id)->first();
    if(!empty($result_)){
    $result = SendHrRequest::where('id', $candidate_id)->first();
    //  dd($request);
    $data = '';
    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    $sr=0;
    $user_id = Auth::user()->id;
    $date = date('Y-m-d H:i:s');
    $files= $request->file('upload_document');
    $attach_ments = [];
    foreach($files as $key => $file){
        //dd($file);
        $newname= rand(100000, 999999);
        $name= $file->getClientOriginalName();
        $name = preg_replace('!\s+!', '-', $name);
        
        $newuploadedfilename='eV_'.$newname.'_'.$name;
        $file->move('public/uploads/upload_lc_mol_doc',$newuploadedfilename);
        $document_title = $request->filename[$key];
        $document_file = $newuploadedfilename;  

        $attach_ments[] = public_path('uploads/upload_lc_mol_doc/' . $newuploadedfilename);
      
        $uploadRequredDoc = new VisaDocument();
        $uploadRequredDoc->candidate_id=$candidate_id; 
        $uploadRequredDoc->title= $document_title; 
        $uploadRequredDoc->file_name = $document_file;
        $uploadRequredDoc->created_by = $user_id; 
        $data = $uploadRequredDoc->save();

    }

    $result1 = SendProEvisaProcessing::where('candidate_id', $candidate_id)->first();
    $result1->status = 1;
    $result1->save();

    }  

    if(!empty($data)){   

    $template_data = [
        'name' => $result->candidate_name,
    ]; 

    $email = $result->candidate_email;
 
    Mail::send(['html'=>'email.evisa_doc'], $template_data, function ($message) use ($email, $template_data, $document_file, $document_title, $attach_ments) {
            $message->to($email)
            ->from("lnxx@gmail.com")
            ->subject('eVisa Documents');

        foreach ($attach_ments as $file) {
           $message->attach($file);
        }
    });

    return response()->json(['status'=>200,'msg'=>'Document Uploaded Successfully','data' => $data]);       
    }else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later']);
    }
} else {

    return response()->json(['status'=>400,'msg'=>'First need to send PRO for eVisa process.']);
}
}

public function uploadLcMolSignedCopy(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
   // dd($request->upload_document);
    $result_ = LcMolDoc::where('candidate_id', $candidate_id)->first();
    if(!empty($result_)){
    $result = SendHrRequest::where('id', $candidate_id)->first();
  //  dd($request);
    $data = '';
    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    $sr=0;
    $user_id = Auth::user()->id;
    $date = date('Y-m-d H:i:s');
    $files= $request->file('upload_document');
    foreach($files as $key => $file){
        //dd($file);
        $newname= rand(100000, 999999);
        $name= $file->getClientOriginalName();
        $name = preg_replace('!\s+!', '-', $name);
        
        $newuploadedfilename='sg_'.$newname.'_'.$name;
        $file->move('public/uploads/upload_lc_mol_doc',$newuploadedfilename);
        $document_title = $request->filename[$key];
        $document_file = $newuploadedfilename;   
  
        $uploadRequredDoc = new SignedLcMolDoc();
        $uploadRequredDoc->candidate_id=$candidate_id; 
        $uploadRequredDoc->title= $document_title; 
        $uploadRequredDoc->file_name = $document_file;
        $uploadRequredDoc->created_by = $user_id; 
        $data=$uploadRequredDoc->save(); 
    }

    $result1 = SendHrRequest::where('id', $candidate_id)->first();
    $result1->hiring_status = 8;
    $result1->save();

    }  

    if(!empty($data)){    
    return response()->json(['status'=>200,'msg'=>'Document Uploaded Successfully','data' => $data]);       
    }else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later']);
    }
} else {

    return response()->json(['status'=>400,'msg'=>'First need to upload LC/MOL documents.']);
}
}

public function UploadLcMol(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
   // dd($request->upload_document);
    $result_ = SendProLcMol::where('candidate_id', $candidate_id)->first();
    if(!empty($result_)){
    $result = SendHrRequest::where('id', $candidate_id)->first();
  //  dd($request);
    $data = '';
    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    $sr=0;
    $user_id = Auth::user()->id;
    $date = date('Y-m-d H:i:s');
    $files= $request->file('upload_document');
    $attach_ments = [];
    foreach($files as $key => $file){
        //dd($file);
            $newname= rand(100000, 999999);
            $name= $file->getClientOriginalName();
            $name = preg_replace('!\s+!', '-', $name);
            
            $newuploadedfilename='_'.$newname.'_'.$name;
            $file->move('public/uploads/upload_lc_mol_doc',$newuploadedfilename);
            $attach_ments[] = public_path('uploads/upload_lc_mol_doc/' . $newuploadedfilename);
            $document_title = $request->filename[$key];
            $document_file = $newuploadedfilename;   
  
        $uploadRequredDoc = new LcMolDoc();
        $uploadRequredDoc->candidate_id=$candidate_id; 
        $uploadRequredDoc->title= $document_title; 
        $uploadRequredDoc->file_name = $document_file;
        $uploadRequredDoc->created_by = $user_id; 
        $data=$uploadRequredDoc->save(); 
    }

    $result1 = SendProLcMol::where('candidate_id', $candidate_id)->first();
    $result1->status = 1;
    $result1->save();

    }  

    if(!empty($data)){  

    $template_data = [
        'name' => $result->candidate_name,
    ]; 

    $email = $result->candidate_email; 

        Mail::send(['html'=>'email.lc_mol_doc'], $template_data, function ($message) use ($email, $template_data, $document_file, $document_title, $attach_ments) {
            $message->to($email)
            ->from("lnxx@gmail.com")
            ->subject('LC/Mol document');
        foreach ($attach_ments as $file) {
        $message->attach($file);
        }
    });

    return response()->json(['status'=>200,'msg'=>'Document Uploaded Successfully','data' => $data]);       
    }else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later']);
    }
} else {

    return response()->json(['status'=>400,'msg'=>'First need to send PRO for LC/MOL process.']);
}
}

public function UploadRequiredDoc(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
   // dd($request->upload_document);
    $result = SendHrRequest::where('id', $candidate_id)->first();

    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    $sr=0;
    $user_id = Auth::user()->id;
    $date = date('Y-m-d H:i:s');
    $files= $request->file('upload_document');
       foreach($files as $key => $file){
        //dd($file);
            $newname= rand(100000, 999999);
            $name= $file->getClientOriginalName();
            $name = preg_replace('!\s+!', '-', $name);
            
            $newuploadedfilename='Req_'.$newname.'_'.$name;
            $file->move('public/uploads/candidate-upload-required-doc',$newuploadedfilename);
            $document_title = $request->filename[$key];
            $document_file = $newuploadedfilename;   
            $document_ids=$request->document_master[$key];  
        // 
        
        $uploadRequredDoc = new CandidateRequiredDocument();
        $uploadRequredDoc->organisation_id=$user_id;
        $uploadRequredDoc->candidate_id=$candidate_id; 
        $uploadRequredDoc->document_id=$document_ids; 
        $uploadRequredDoc->document_title= $document_title; 
        $uploadRequredDoc->document_file = $document_file;
        $uploadRequredDoc->doc_upload_date = $date;
        $uploadRequredDoc->created_by = 'HR'; 
        $data=$uploadRequredDoc->save(); 

    }

    }  

    if(!empty($data)){    
    return response()->json(['status'=>200,'msg'=>'Document Uploaded Successfully','data' => $data]);       
    }else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later']);
    }
}




public function UploadSignedDoc(Request $request){  
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_id;
    $document_ids=$request->document_master;
    $result = SendHrRequest::where(['organisation_id'=>$user_id,'id'=>$candidate_id])->first();
    $input=$request->all();
    $images=array();
    if(!empty($result)){  
    if($files=$request->file('upload_document')){ 
        $date = date('Y-m-d H:i:s');
        $newname= rand(100000, 999999);
        $name=$files->getClientOriginalName();
        $name = preg_replace('!\s+!', '-', $name);
        $newuploadedfilename='Signed_'.$newname.'_'.$name;
        $files->move('public/uploads/candidate-upload-required-doc',$newuploadedfilename);
        $document_title= $request->filename;
        $document_file= $newuploadedfilename;     

        $user_id = Auth::user()->id;
        $uploadSignedDoc = new CandidateRequiredDocument();
        $uploadSignedDoc->organisation_id=$user_id;
        $uploadSignedDoc->candidate_id=$candidate_id; 
        $uploadSignedDoc->document_id=$document_ids; 
        $uploadSignedDoc->document_title= $document_title; 
        $uploadSignedDoc->document_file = $document_file;
        $uploadSignedDoc->doc_upload_date = $date;
        $uploadSignedDoc->created_by = 'HR'; 
        $data=$uploadSignedDoc->save();      
    }
    }  

    if(!empty($data)){    
    return response()->json(['status'=>200,'msg'=>'Scanned Document Uploaded Successfully','data' => $data]);       
    }else{  $msg="This document already uploaded.";
    return response()->json(['status'=>400,'msg'=>'Something went wrong! Please try later','data' => $data]);
    }
}

public function SendMedicalTestAppointment(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id = $request->candidate_rec_id;
    $appointment_time = $request->appointment_time;
    $comments = $request->comments;
    $place = $request->place;
    
    $result = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $data = '';

    if(!empty($result)){  
    $get_duplicate = MedicalAppointment::where('candidate_id', $candidate_id)->first(); 
    //$pos_id = $result->candidate_position_id;

    $result1 = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $result1->hiring_status = 10;
    $result1->save();


    if(empty($get_duplicate)){  
       // $managers=User::where('id', $manager_name_id)->first(); 
        $new_names = '';
        if($file=$request->file('attachment')){

        $newfilename = rand(100000, 999999);
        $name=$file->getClientOriginalName();
        $new_names='medical_'.$newfilename.'_'.$name;

        $file->move('public/uploads/medical_test',$new_names);
        
        }

        $sendvisaApproval = new MedicalAppointment();
        $sendvisaApproval->created_by = $user_id;
        $sendvisaApproval->candidate_id = $result->id;
        $sendvisaApproval->place = $place;
        $sendvisaApproval->comments = $comments;
        $sendvisaApproval->status  =  0;
        $sendvisaApproval->appointment_time  =  $appointment_time;
        $sendvisaApproval->attachment = $new_names; 

        $data=$sendvisaApproval->save(); 
        $msg="Details send to candidate for medical test appointment";
      } 
      else {
        $msg="Already details send to candidate for medical test appointment.";
        $data='';
      }         

    }

    if(!empty($data)){

        $template_data = [
            'name'             => $result->candidate_name,
            'comments'         => $comments,
            'appointment_time' => $appointment_time,
            'place' => $place,  
        ];

        $email = $result->candidate_email;

        if($new_names != ''){
            $document_title = 'Medical Test Appointment';
            $img_p = public_path('uploads/medical_test/' . $new_names);
            Mail::send(['html'=>'email.medical_test_appointment'], $template_data, function ($message) use ($email, $template_data, $new_names, $document_title, $img_p) {
            $message->to($email)
            ->from("lnxx@gmail.com")
            ->subject('Medical Test Appointment');
            $message->attach($img_p);
            // $message->attachData(public_path('uploads/medical_test/' . $new_names), $document_title);
            });

        } else {

            Mail::send(['html'=>'email.medical_test_appointment'], $template_data, function ($message) use ($email, $template_data) {
            $message->to($email)->from("lnxx@gmail.com")->subject('Medical Test Appointment');
            });

        }
       return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);      
    } else {
          $msg="Already details send to candidate for medical test appointment.";
        return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
    }

}

public function SendProRequestForEidProcess(Request $request){
    $user_id = Auth::user()->id;


    $candidate_id=$request->candidate_rec_id;
    
    $comments=$request->comments;
    $result = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $date = date('Y-m-d H:i:s');
    $data = '';
    
    $mr = MedicalReport::where('candidate_id', $candidate_id)->count();
    if($mr != 0){
    if(!empty($result)){  
    $get_duplicate = EidProcesse::where('candidate_id', $candidate_id)->first(); 
    $pos_id = $result->candidate_position_id;

    $result1 = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $result1->hiring_status = 11;
    $result1->save();
    

    if(empty($get_duplicate)){  
        foreach ($request->manager_name as $manager_name) {

            $manager_name_id = $manager_name;
            if($request->agency == 0) {
                $user = User::where('id', $manager_name_id)->select('name', 'email')->first();
                $managers=User::where('id', $manager_name_id)->first(); 
            } else {
                $user = VanderStaff::where('id', $manager_name_id)->select('name', 'email')->first();
                $managers= VanderStaff::where('id', $manager_name_id)->first(); 
            }
            $manager_email=$managers->email;
            $sendvisaApproval = new EidProcesse();
            $sendvisaApproval->request_send_by = $user_id;
            $sendvisaApproval->candidate_id = $result->id;
            $sendvisaApproval->agency = $request->agency;
            $sendvisaApproval->pro_id = $manager_name_id;
            $sendvisaApproval->pro_email = $user->email;
            $sendvisaApproval->comments = $comments;
            $data=$sendvisaApproval->save(); 
            $msg="Details send to PRO for E-ID processing";
        }
      } 
      else {
        $msg="Already Details send for E-ID processing.";
        $data='';
      }         
    }

    if(!empty($data)){
            $getdata = EidProcesse::where('candidate_id', $candidate_id)->first();
        if(!empty($getdata)) {


        $position = PositionMaster::where('id', $pos_id)->select('position_name')->first();
        $candidate_id = \encrypt($candidate_id);

        foreach ($request->manager_name as $manager_name) {
        
        if($request->agency == 0) {
                $user = User::where('id', $manager_name)->select('name', 'email')->first();
            } else {
                $user= VanderStaff::where('id', $manager_name)->select('name', 'email')->first(); 
        }

        $template_data = [
            'managername'   => $user->name,
            'name'          => $result->candidate_name,
            'comments'      => $comments,
            'encrypt_key'   => $candidate_id,
            'position_name' => $position->position_name,
        ];


        $email = $user->email;

        Mail::send(['html'=>'email.eid_process_request'], $template_data,
            function ($message) use ($email,$template_data) {
                $message->to($email)->from("lnxx@gmail.com")->subject('E-ID Process Request of' .$template_data['name']);
        });

        }

        } else{
            $msg='Something went wrong';
        }

        return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);       
        }else{  $msg="Details Already send for E-ID processing";
        return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
        }
    } else {

        $msg="First need to upload medical reports.";
        return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
    }
}

public function SendProRequestForEvisaProcess(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_rec_id;

    $comments=$request->comments;
    $result = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $date = date('Y-m-d H:i:s');
    $data = '';
    
    if(!empty($result)){  
        $get_duplicate = SendProEvisaProcessing::where('candidate_id', $candidate_id)->first(); 
        $pos_id = $result->candidate_position_id;
        $result1 = SendHrRequest::where('id', $request->candidate_rec_id)->first();
        $result1->hiring_status = 9;
        $result1->save();

        if(empty($get_duplicate)){  
            foreach ($request->manager_name as $manager_name) {
                $manager_name_id=$manager_name;
                $managers=User::where('id', $manager_name_id)->first(); 
                $manager_email=$managers->email;
                $sendvisaApproval = new SendProEvisaProcessing();
                $sendvisaApproval->created_by = $user_id;
                $sendvisaApproval->agency = $request->agency;
                $sendvisaApproval->candidate_id = $result->id;
                $sendvisaApproval->pro_id = $manager_name_id;
                $sendvisaApproval->comments = $comments;
                $sendvisaApproval->status =  0;
                $data=$sendvisaApproval->save(); 
                $msg="Details send to PRO for eVisa processing";
            }
        } else {
            $msg="Already Details send for eVisa processing.";
            $data='';
        }         
    }

    if(!empty($data)){
            $getdata = SendProEvisaProcessing::where('candidate_id', $candidate_id)->get();
        if(!empty($getdata)) {
            $this->SendProeVisaProcessMail($getdata, $pos_id, $manager_email, $result->candidate_name);
        }else{
            $msg='Something went wrong';
        }

        return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);       
        }else{  $msg="Details Already send for eVisa processing";
        return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
        }
}

public function SendProeVisaProcessMail($data, $pos_id, $email, $candidate_name){
    $user_id = Auth::user()->id;
    try {
     
    foreach ($data as $data) {

    if($data->agency == 0) {
        $managers = User::where('id', $data->pro_id)->first(); 
    } else {
        $managers = VanderStaff::where('id', $data->pro_id)->first(); 
    }
    

    $email = $managers->email;
    $manager_name = $managers->name;
    $rec_id = $data->id;
    $candidate_id = \encrypt($data->candidate_id);
   
    $position = PositionMaster::where('id', $pos_id)->select('position_name')->first(); 
    $template_data = [
        'managername'   => $manager_name,
        'name'          => $candidate_name,
        'comments'      => $data->comments,
        'encrypt_key'   => $candidate_id,
        'position_name' => $position->position_name,
    ];

    Mail::send(['html'=>'email.evisa_process_request'], $template_data,
        function ($message) use ($email,$template_data) {
            $message->to($email)->from("lnxx@gmail.com")->subject('eVisa Process Request of' .$template_data['name']);
    }); 

    }
    return true;
    } catch (Exception $ex) {
    return false;
    }  
}


public function SendProRequestForLcMol(Request $request){
   
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_rec_id;
    $manager_name_id=$request->manager_name;
    $comments=$request->comments;
    $result = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $date = date('Y-m-d H:i:s');

    if(!empty($result)){  
    $get_duplicate = SendProLcMol::where('candidate_id', $candidate_id)->first(); 
    $pos_id = $result->candidate_position_id;
    $result1 = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $result1->hiring_status = 7;
    $result1->save();

    if(empty($get_duplicate)){  

        foreach ($request->manager_name as $manager) {
            if($request->agency == 0){
                $managers = User::where('id', $manager)->first(); 
            } else {
                $managers = VanderStaff::where('id', $manager)->first();
            }
            $manager_email=$managers->email;
            $sendvisaApproval = new SendProLcMol();
            $sendvisaApproval->request_send_by = $user_id;
            $sendvisaApproval->candidate_id = $result->id;
            $sendvisaApproval->agency = $request->agency;
            $sendvisaApproval->candidate_name = $result->candidate_name;
            $sendvisaApproval->pro_id = $manager;
            $sendvisaApproval->pro_email = $manager_email;
            $sendvisaApproval->comments = $comments;
            $sendvisaApproval->status  =  0;
            $data=$sendvisaApproval->save(); 

            $msg="Details send to PRO for LC/MOL process.";

        } 

      }  else {
        $msg="Already Details send for LC/MOL process.";
        $data='';
      }         

    }  
      if(!empty($data)){
            $getdata = SendProLcMol::where('candidate_id', $candidate_id)->get();
        if(!empty($getdata)) {
            $this->SendProLcMolMail($getdata, $pos_id);
        }else{
            $msg='Something went wrong';
        }

        return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);       
        }else{  $msg="Details Already send for LC/MOL process.";
        return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
        }
}




public function UploadDocumentForVisaApproval(Request $request){
    $user_id = Auth::user()->id;
    $candidate_id=$request->candidate_rec_id;
    $manager_name_id=$request->manager_name;
    $comments=$request->comments;
    $result = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $date = date('Y-m-d H:i:s');

    if(!empty($result)){  

    $get_duplicate = SendVisaApproval::where(['candidate_id'=>$candidate_id,'status'=>'0'])->first(); 

    $pos_id = $result->candidate_position_id;

    $result1 = SendHrRequest::where('id', $request->candidate_rec_id)->first();
    $result1->hiring_status = 4;
    $result1->save();


    if(empty($get_duplicate)){  
        $managers=User::where('id', $manager_name_id)->first(); 
        $manager_email=$managers->email;
        $sendvisaApproval = new SendVisaApproval();
        $sendvisaApproval->organisation_id = $user_id;
        $sendvisaApproval->candidate_id = $result->id;
        $sendvisaApproval->name = $result->candidate_name;
        $sendvisaApproval->manager_id = $manager_name_id;
        $sendvisaApproval->manager_email = $manager_email;
        $sendvisaApproval->comments = $comments;
        $sendvisaApproval->candidate_profile_url = 'candidate-common-profile-details/'.$result->id;
        $sendvisaApproval->status  =  '1';
        $sendvisaApproval->created_at = $date;
        $data=$sendvisaApproval->save(); 
        $msg="Details send for Visa approval.";
       /* $results = DB::table('send_hr_requests')
        ->where(['organisation_id'=>$user_id,'id'=>$result->id])
        ->update(['hiring_status' => '4',]);*/
      } 
      else {
        $msg="Already Details send for Visa approval.";
        $data='';
      }         

    }  
      if(!empty($data)){
            $getdata = SendVisaApproval::where(['organisation_id'=>$user_id,'candidate_id'=>$candidate_id,'status'=>'1'])->first();
        if(!empty($getdata)) {
            $this->SendeVisaApprovalMail($getdata, $pos_id);
        }else{
            $msg='Something wend wrong to send mail.';
        }

        return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);       
        }else{  $msg="Details Already send for eVisa approval.";
        return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
        }

}


public function SendProLcMolMail($data, $pos_id){
    try {
    $user_id = Auth::user()->id;
    foreach($data as $data) {
    $email = array($data->pro_email);
    if($data->agency == 0){
        $managers = User::where('id', $data->pro_id)->first(); 
    } else {
        $managers = VanderStaff::where('id', $data->pro_id)->first();
    }
    $manager_name = $managers->name;
    $rec_id = $data->id;
    $candidate_id = \encrypt($data->candidate_id);
    

    $position = PositionMaster::where('id', $pos_id)->select('position_name')->first(); 
    $template_data = [
        'managername'   => $manager_name,
        'name'          => $data->candidate_name,
        'comments'      => $data->comments,
        'encrypt_key'   => $candidate_id,
        'position_name' => $position->position_name,
    ];

    Mail::send(['html'=>'email.lc_mol_process_request'], $template_data,
        function ($message) use ($email,$template_data) {
            $message->to($email)->from("lnxx@gmail.com")->subject('LC/MOL Process Request of' .$template_data['name']);
    }); 
    }

    return true;
    } catch (Exception $ex) {
    return false;
    }  
 }


public function SendeVisaApprovalMail($data, $pos_id){
//echo "<pre>"; print_r($data); echo "</pre>";  die;
    $user_id = Auth::user()->id;
    $email = array($data->manager_email);
    $managers=User::where(['organisation_id'=>$user_id,'email'=>$email])->first(); 
    $manager_name=$managers->name;
    //$candidate_profile_url='candidate-common-profile-share-link/'.$data->id;
    $candidate_profile_url='';
    $rec_id=$data->id;
    $organisation_id=$data->organisation_id;
    $candidate_id=\encrypt($data->candidate_id);
    try {

    $position = PositionMaster::where('id', $pos_id)->select('position_name')->first(); 
    $template_data = [
        'managername'       => $manager_name,
        'name'              => $data->name,
        'comments'          => $data->comments,
        'candidatedetails'  => $candidate_profile_url,
        'encrypt_key'       => $candidate_id,
        'position_name'       => $position->position_name,
     ];

     Mail::send(['html'=>'email.evisaapproval'], $template_data,
        function ($message) use ($email,$template_data) {
            $message->to($email)->from("lnxx@gmail.com")->subject($template_data['managername'].' '.$template_data['name'].' '.$template_data['comments'].'  '.$template_data['candidatedetails']);
    }); 
    return true;
    } catch (Exception $ex) {
    return false;
    }  
 }


    #############################END VIKAS NEW CODES#######################################

     public function emailTemplateStatus(Request $request){
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('orgnization_id', $user_id)->update(['email_template' => $request->email_template]);
        }else{
            $template_master = new TemplateMaster();
            $template_master->orgnization_id = $user_id;
            $template_master->email_template = $request->email_template;
            $template_master->save();
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }

    public function emailTemplateSetting(Request $request){ 
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->update(['email_template' => $request->email_template]);
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }

    public function smsTemplateSetting(Request $request){ 
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->update(['sms_template' => $request->sms_template]);
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }

    public function notificationTemplateSetting(Request $request){ 
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->update(['notification_template' => $request->notification_template]);
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }


    public function PostSortable(Request $request){
        $posts = FormEngine::all();
        foreach ($posts as $post) {
            $sele = FormEngine::where('id',$post->id)->first();
            if($sele->order_id==0){
                $sele->order_id = $sele->id;
                $sele->save();
            }
            foreach ($request->order as $order) {
                $sele = FormEngine::where('id',$order['id'])->first();
                if(!empty($sele)){
                    $sele->order_id = $order['position'];
                    $sele->save();
                }
            }
        }
        return response()->json(['status'=>400]);
    }

    public function smsTemplateStatus(Request $request){
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('orgnization_id', $user_id)->update(['sms_template' => $request->sms_template]);
        }else{
            $template_master = new TemplateMaster();
            $template_master->orgnization_id = $user_id;
            $template_master->sms_template = $request->sms_template;
            $template_master->save();
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }
    public function notificationTemplateStatus(Request $request){
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('orgnization_id', $user_id)->update(['notification_template' => $request->notification_template]);
        }else{
            $template_master = new TemplateMaster();
            $template_master->orgnization_id = $user_id;
            $template_master->notification_template = $request->notification_template;
            $template_master->save();
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }
    public function GetEmailTemplateList(Request $request){
        $user_id = Auth::user()->id;
        $data = EmailTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function getTemplateMasters(Request $request){
        $user_id = Auth::user()->id;
        $data = EmailTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }






    public function GetSMSTemplateList(Request $request){
        $user_id = Auth::user()->id;
        $data = SmsTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetNotificationemplateList(Request $request){
        $user_id = Auth::user()->id;
        $data = NotificationTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetStatusNotificationemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = NotificationTemplate::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusEmailTemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = EmailTemplate::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusSMSTemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = SmsTemplate::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function HeaderTemplate(Request $request){ 
        $user_id = Auth::user()->id;

        $data = new HeaderFooterMaster();
        $data->orgnization_id = Auth::user()->id;
        dd($_FILES["header_image"]["name"]);
        if(!empty($_FILES['header_image']['name'])){
            $headerfilenames = time().'.'.$request->header_image->extension();
            $request->header_image->move(public_path('organization/header_image'),$headerfilenames);
            if(!empty($headerfilenames)){
                $data->header_image = $headerfilenames;
            }
        }
        if($request->hasFile('footer_image')){
            $footerfilenames = time().'.'.$request->footer_image->extension();
            $request->footer_image->move(public_path('organization/footer_image'),$footerfilenames);
            if(!empty($footerfilenames)){
                $data->footer_image = $footerfilenames;
            }
        }
        $data->save();

        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetReportingUser($user_id){
        $reporting = DB::select("SELECT a.orgnization_id,a.reporting_id,b.email as report_email,b.name as report_name,c.name as org_name,c.email as org_email FROM `emp_reportings` as a INNER JOIN users as b on a.reporting_id=b.id INNER JOIN users as c on a.orgnization_id=c.id WHERE JSON_CONTAINS(a.employee_id,$user_id)=1");
        if(!empty($reporting[0])){
            return $reporting[0];
        }else{
            return array();
        }
    }
    public function SendAttendanceMail($data){
        $email = array($data->org_email, $data->report_email);
        try {
            $template_data = [
                'report_email'  => $data->report_email,
                'report_name'   => $data->report_name,
                'org_name'      => $data->org_name,
                'org_email'     => $data->org_email,
                'user_name'     => Auth::user()->name
            ];
            Mail::send(['html'=>'email.attendance'], $template_data,
                function ($message) use ($email,$template_data) {
                    $message->to($email)->from("vikaspyadava@gmail.com")->subject($template_data['user_name'].' marked attendance on '.date('d-M-Y'));
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function SendAttendanceMailToEmployee($attendance_type){
        $emp = Auth::user();
        $email = $emp->email;
        try {
            $template = [
                'attendance_type'   => $attendance_type,
                'name'              => $emp->name
            ];
            Mail::send(['html'=>'email.emp_attendance'], $template,
                function ($message) use ($email,$template) {
                    $message->to($email)->from("vikaspyadava@gmail.com")->subject($template['name'].' marked '.$template['attendance_type'].' attendance on '.date('d-M-Y'));
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function CheckEmail(Request $request){
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status'=>401,'message'=>'Please enter valide email-id']);exit;
        }
        $slelect = User::where(['email'=>$request->email])->first();
        if(!empty($slelect)){
            return response()->json(['status'=>404,'message'=>'Unavailable !']);
        }else{
            return response()->json(['status'=>200,'message'=>'Available !']);
        }
    }
    public function CheckUsername(Request $request){
        $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
        if (preg_match($pattern, $request->user_name)){
            return response()->json(['status'=>401,'message'=>'Username should not contain any special characters, symbols or spaces']);exit;
        }
        $slelect = Organisation::where(['user_name'=>$request->user_name])->first();
        if(!empty($slelect)){
            return response()->json(['status'=>404,'message'=>'Unavailable !']);
        }else{
            return response()->json(['status'=>200,'message'=>'Available !']);
        }
    }
    public function OrganisationDetails(){
        $data = DB::select("SELECT a.id,a.name,b.user_name,a.email,a.mobile,b.logo,b.address,a.created_at,a.updated_at,b.status,b.id as m_id  FROM users as a INNER JOIN organisations as b on a.id=b.user_id WHERE type=1 ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function EmployeeDetails(){
        $user_id = Auth::user()->id;
       // $data = DB::select("SELECT a.id,b.employee_code,a.name,a.email,a.mobile,a.status,a.created_at FROM `users` as a INNER JOIN employee_infos as b on a.id=b.user_id WHERE b.employee_code is NOT null AND a.organisation_id=$user_id ORDER BY a.id DESC");
       $data = DB::select("SELECT a.id,e.position_name,c.office_name,d.department_name,b.employee_code,a.name,a.email,a.mobile,a.status,a.created_at FROM `users` as a INNER JOIN employee_infos as b on a.id=b.user_id INNER JOIN office_masters as c on b.office_id = c.id INNER JOIN department_masters as d on b.department_id = d.id INNER JOIN position_masters as e on b.position_id = e.id WHERE b.employee_code is NOT null AND a.organisation_id=$user_id ORDER BY a.id DESC"); 
       return response()->json(['data' => $data]);
    }
    
    //28-11-2022 Ashutosh Start
    public function UserDetails(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.gender,b.dob,b.father_name,b.mother_name,b.profile FROM `users` as a INNER JOIN emp_details as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserContact(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.mobile,b.father_mobile,b.friend_mobile,b.address,b.pincode,c.name AS cityName,d.name AS stateName FROM users as a JOIN emp_contacts as b ON a.id = b.user_id JOIN cities as c ON c.id = b.city_id JOIN states as d ON d.id = b.state_id where b.user_id=$user_id ORDER BY a.id DESC;");
        return response()->json(['data' => $data]);
    }
    public function UserDocument(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.doucment_title,b.doucment_file FROM `users` as a INNER JOIN emp_documents as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserEducation(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.education_type,b.course_name,b.board_university,b.from_year,b.to_year,b.percentage_cgpa,b.document FROM `users` as a INNER JOIN emp_educations as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserBank(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.acc_holder_name,b.bank_id,b.acc_number,b.ifsc_code,b.pan_number,b.branch_name,b.status FROM `users` as a INNER JOIN emp_banks as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserCompany(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.comp_name,b.designation,b.date_of_joining,b.date_of_resignation,b.ctc,b.reason_for_leav_comp FROM `users` as a INNER JOIN emp_companies as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserAssetsRequestList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,a.status FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id where a.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function viewAssetsData(Request $request){
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,c.name,e.employee_code,c.email,c.mobile,a.status,a.description_admin,a.start_date_admin,a.end_date_admin FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id INNER JOIN users AS c ON a.user_id=c.id INNER JOIN employee_infos as e on e.user_id=a.user_id WHERE e.employee_code is not null AND a.id=$request->id ORDER BY a.id DESC;");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    //28-11-2022 Ashutosh End

    //29-11-2022 Ashutosh Start
    public function UserLetterList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM letter_masters where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    public function UserOfficerSignatureList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM officer_signatures where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    public function UserLetterTemplateList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM letter_templates where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    public function UserMapLetterTemplateList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM map_letter_templates where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    //29-11-2022 Ashutosh End
    
    public function GetState(Request $request){
        $data = State::select(['id','name'])->where(['country_id'=>$request->country_id])->orderBy('name', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetCity(Request $request){
        $data = City::select(['id','name'])->where(['state_id'=>$request->state_id])->orderBy('name', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function MarkAttendance(Request $request){
        $date = date('Y-m-d');
        $curren_time = date('H:i:s');
        $user_id = Auth::user();

        $image_64 = $request->snapshot;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        $image = str_replace($replace, '', $image_64); 
        $image = str_replace(' ', '+', $image); 
        $imageName = str_replace(' ', '_',Str::lower(Auth::user()->name)).'_'.Str::lower(Str::random(10)).'.'.$extension;
        Storage::disk('attendance')->put($imageName, base64_decode($image));
        
        if(empty($request->latitude) && empty($request->longitude)){
            return response()->json(['status'=>400,'message'=>'Please Turn On Your Location']);exit;
        }

        $attendance = DB::select("SELECT id,TIMEDIFF('$curren_time',in_time) as totaltime from `emp_attendances` WHERE DATE(created_at) = '$date' AND user_id=$user_id->id LIMIT 1");
        if(!empty($attendance[0])){
            $emp_attendance = EmpAttendance::where(['id'=>$attendance[0]->id])->first();
            $emp_attendance->user_id = $user_id->id;
            $emp_attendance->out_time = $curren_time;
            $emp_attendance->out_image = $imageName;
            $emp_attendance->out_latitude = $request->latitude;
            $emp_attendance->out_longitude = $request->longitude;
            $emp_attendance->total_time = $attendance[0]->totaltime;
            $emp_attendance->save();
            $this->SendAttendanceMailToEmployee('out');
            $not['title']='Attendance Marked Successfully';

            $not['body']='Dear '.$user_id->name.' you have successfully marked attendance out';
            
            $this->SendPushNotification($not,$user_id,2);
            return response()->json(['status'=>200,'message'=>'Successfully Attancdance Marked Out','attendance'=>'OUT']);
        }else{
            $emp_attendance = new EmpAttendance();
            $emp_attendance->user_id = $user_id->id; 
            $emp_attendance->in_time = $curren_time;
            $emp_attendance->in_image = $imageName;
            $emp_attendance->in_latitude = $request->latitude;
            $emp_attendance->in_longitude = $request->longitude;
            $emp_attendance->save();
            $this->SendAttendanceMailToEmployee('in');
            $not['title']='Attendance Marked Successfully';
            $not['body']='Dear '.$user_id->name.' you have successfully marked attendance in';
            $this->SendPushNotification($not,$user_id,2);
            return response()->json(['status'=>200,'message'=>'Successfully Attancdance Marked In','attendance'=>'IN']);
        }
    }
    public function EmployeeAttendances(){
        $data = EmpAttendance::select('id','user_id','in_time','total_time','out_time','in_image','out_image','created_at')->where(['user_id'=>Auth::user()->id])->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function AllEmployeeAttendances(){
        $orgnaization = Auth::user()->id;
        $emp_detail = EmpDetail::select('user_id')->where('created_by',$orgnaization)->get();
        if(!empty($emp_detail)){
            foreach($emp_detail as $row){
                $data[]=$row->user_id;
            }
            $users_id = implode(',',$data);
            $data = EmpAttendance::select('id','user_id','in_time','total_time','out_time','in_image','out_image','created_at')->whereIn('user_id', $data)->orderBy('id', 'DESC')->get();
        }else{
            $data=[];
        }
        return response()->json(['data' => $data]);

        //tarika garalat hai
    }
    public function EmployeeLeaves(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.status,b.name FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id WHERE a.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }

    public function GetLeaveReason(Request $request){
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.created_at,a.status,b.name as leave_type,c.name,c.mobile FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id INNER JOIN users as c on c.id=a.user_id WHERE a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetActivity(Request $request){
        $data = ProjectActivity::select('id','activity_name')->where(['id'=>$request->project_id])->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function ViewTimesheet(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_time,a.end_time,a.duration,a.description,a.status,b.project_name,c.activity_name,a.created_at FROM `timeseets` as a INNER JOIN project_masters as b on a.project_id=b.id INNER JOIN project_activities as c on b.id=c.project_id WHERE a.user_id=$user_id GROUP BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function GetTimesheetData(Request $request){
        $data = Timeseet::select('description')->where(['id'=>$request->id])->first();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetSourceMasters(){
        $user_id = Auth::user()->id;
        $data = SourceMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetNoticeMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.notice_days,a.is_default,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,d.position_name FROM `notice_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id INNER JOIN position_masters AS d ON d.id=a.position_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEducationMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.is_default,a.id,a.education_title,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,d.position_name FROM `education_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id INNER JOIN position_masters AS d ON d.id=a.position_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmployeeAllDetails(Request $request){
        $user_id = $request->segment(3);
        $orgnaization = Auth::user()->id;
        $form_category = FormEngineCategory::select('id','name','is_multiple')->where('orgnization_id', $orgnaization)->orderBy('is_multiple', 'ASC')->get();
        foreach($form_category as $formcategory){
            $data = EmployeeInfo::select('datas')->where('organisation_id',$orgnaization)->where('user_id',$user_id)->where('from_cat_id',$formcategory->id)->first();
            if($formcategory->is_multiple==1){
                $emp = @json_decode($data->datas);
                ?>
                <div class="col-md-12 mb-3">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <h5 class="mb-2"> <?=$formcategory->name;?> </h5>
                            <div class="row">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                        <?php if(!empty($emp)){ foreach($emp as $x => $val){
                                            $form = FormEngine::select('form_name')->where('form_column',$x)->first();
                                            echo '<td><b>'.$form->form_name.'</b></td>';
                                        } } ?>
                                        </tr>
                                    </thead>
                                    <tbody><tr>
                                        <?php if(!empty($emp)){ foreach($emp as $x => $val){
                                            $form = FormEngine::select('form_name','data_type')->where('form_column',$x)->first();
                                            $count = count($val);
                                            echo '<td>';
                                            for($i=0;$i < $count;$i++){
                                                if($form->data_type=='file'){
                                                    echo '<p><a href="'.url(@$val[$i]).'" download>Download</a></p>';
                                                }elseif($form->data_type=='date'){
                                                    echo '<p >'.date_format(date_create(@$val[$i]),"d-M-Y").'</p>';
                                                }else{
                                                    echo '<p >'.@$val[$i].'</p>';
                                                }
                                            }
                                            echo '</td>';
                                        } } ?></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }else{ $emp1 = @json_decode($data->datas);?>
                <div class="col-md-6 mb-3">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <h5 class="mb-2"> <?=$formcategory->name;?> </h5>
                            <div class="row">
                                <table class="table table-condensed"><tbody>
                                <?php if(!empty($emp1)){    foreach($emp1 as $x => $val){
                                    
                                    $form = FormEngine::select('form_name','data_type')->where('form_column',$x)->first();
                                    if(!empty($form)){
                                    if($form->data_type=='file'){
                                        $valdata = '<p><a href="'.url(@$val).'" download>Download</a></p>';
                                    }elseif($form->data_type=='date'){
                                        $valdata = '<p >'.date_format(date_create(@$val),"d-M-Y").'</p>';
                                    }else{
                                        $valdata = '<p >'.@$val.'</p>';
                                    }
                                    echo '<tr>
                                            <td><b>'.$form->form_name.'</b></td>
                                            <td>'.$valdata.'</td>
                                        </tr>';
                                  }
                                } } ?>
                                </tbody></table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        }
    }
    // public function GetEmployeeAllDetails(Request $request){
    //     $user_id = $request->segment(3);

    //     $personal = DB::select("SELECT a.name,a.email,a.mobile,b.gender,b.dob,b.father_name,b.mother_name,b.profile,b.salary,c.position_name,d.source_name,e.notice_days FROM `users` as a INNER JOIN emp_details as b on a.id=b.user_id INNER JOIN position_masters as c on b.designation_id=c.id INNER JOIN source_masters as d on b.source_id=d.id INNER JOIN notice_masters as e on b.notice_id=e.id WHERE a.id=$user_id");

    //     $contact = DB::select("SELECT mobile,father_mobile,friend_mobile,address,pincode,stateName,cityName FROM `emp_contacts` as a INNER JOIN states as b on a.state_id=b.stateID INNER JOIN cities as c on a.city_id=c.cityID WHERE a.user_id=$user_id");

    //     $bank = DB::select("SELECT a.acc_holder_name,b.name,a.acc_number,a.ifsc_code,a.pan_number,a.branch_name FROM `emp_banks` as a INNER JOIN bank_masters as b on a.bank_id=b.id WHERE a.user_id=$user_id");

    //     $education = DB::select("SELECT b.education_title,a.course_name,a.board_university,a.from_year,a.to_year,a.percentage_cgpa,a.document FROM `emp_educations` as a INNER JOIN education_masters as b on a.education_type=b.id WHERE a.user_id=$user_id");

    //     $companies = DB::select("SELECT comp_name,designation,date_of_joining,date_of_resignation,ctc,reason_for_leav_comp FROM `emp_companies` WHERE user_id=$user_id ORDER BY id DESC");

    //     $emp_document = EmpDocument::select('doucment_title','doucment_file')->where('user_id',$user_id)->get();

    //     return response()->json([
    //         'status'=>200,
    //         'personal' => !empty($personal[0]) ? $personal[0]:[],
    //         'contact' => !empty($contact[0]) ? $contact[0]:[],
    //         'bank' => !empty($bank[0]) ? $bank[0]:[],
    //         'education' => !empty($education) ? $education:[],
    //         'companies' => !empty($companies) ? $companies:[],
    //         'emp_document' => !empty($emp_document) ? $emp_document:[],
    //     ]);
    // }
    public function GetEmployeeAttendanceData(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.*,CONCAT(c.employee_code, ' - ', b.name) AS names,d.office_name,e.department_name FROM `emp_attendances` as a INNER JOIN users as b on a.user_id=b.id INNER JOIN employee_infos as c on a.user_id=c.user_id INNER JOIN office_masters as d on d.id=c.office_id INNER JOIN department_masters as e on e.id=c.department_id WHERE c.employee_code is NOT null AND c.organisation_id=$user_id AND c.office_id=$request->office_id AND c.department_id=$request->department_id AND a.user_id=$request->emp_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmployeeAttendanceDetails(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->emp_id)){
           $data = DB::select("SELECT a.in_status,a.out_status,a.id,b.organisation_id,c.employee_code,b.name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at FROM emp_attendances as a INNER JOIN users as b on a.user_id=b.id INNER JOIN employee_infos as c on b.id = c.user_id WHERE a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year group by a.user_id ORDER BY id DESC");
            //$data = DB::select("SELECT id,user_id,in_time,total_time,out_time,in_image,out_image,created_at FROM emp_attendances WHERE user_id=$request->emp_id AND MONTH(created_at)=$request->month AND YEAR(created_at)=$request->year ORDER BY id DESC");
        }else{
             $data = DB::select("SELECT a.in_status,a.out_status,a.id,b.organisation_id,c.employee_code,b.name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at FROM emp_attendances as a INNER JOIN users as b on a.user_id=b.id INNER JOIN employee_infos as c on b.id = c.user_id WHERE b.organisation_id =$user_id group by a.user_id ORDER BY id DESC");
   //  $data = DB::select("SELECT id,user_id,in_time,total_time,out_time,in_image,out_image,created_at FROM emp_attendances ORDER BY id DESC");
        }
        return response()->json(['status'=>200,'data' => $data]);
    }
    
    
    public function GetLetterPreview(Request $request){
        $data = LetterTemplate::select('description')->where('id',$request->id)->first();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetProjectMaster(){
        $user_id = Auth::user()->id;
        $data = DB::select('SELECT a.project_name,a.task_master,a.start_date,a.end_date,a.id,a.status,b.office_name,c.department_name FROM `project_masters` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id WHERE a.orgnization_id='.$user_id.' ORDER BY a.id DESC');
        return response()->json(['status'=>200,'data' => $data]);
    }

    public function getVanderList(){
        $user_id = Auth::user()->id;
        $data = DB::select('SELECT a.name,a.address,a.id,a.status FROM `vanders` AS a ORDER BY a.id DESC');
        return response()->json(['status'=>200,'data' => $data]);
    }

    public function getVanderStaffList(){
        $user_id = Auth::user()->id;
        $data = DB::select('SELECT a.name,a.email,a.mobile,a.id,b.name as vander_name,a.status FROM `vander_staffs` AS a INNER JOIN vanders AS b ON a.vander_id=b.id ORDER BY a.id DESC');
        return response()->json(['status'=>200,'data' => $data]);
    }
    
    

    public function ViewEmpTimesheet(Request $request){
        $user_id = Auth::user()->id;

        // $data = DB::select("SELECT d.name,a.id,a.start_time,a.end_time,a.duration,a.description,a.status,b.project_name,c.activity_name,a.created_at FROM `timeseets` as a INNER JOIN project_masters as b on a.project_id=b.id INNER JOIN project_activities as c on b.id=c.project_id INNER JOIN users as d on d.id=a.user_id WHERE a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year ORDER BY a.id DESC");

        $m_length = strlen($request->month);

        if($m_length == 1){
            $month = "0".$request->month;
        } else {
            $month = $request->month;
        }


        $data = \DB::table('timeseets')
            ->join('users', 'users.id', '=', 'timeseets.user_id')
            ->join('project_activities', 'project_activities.id', '=', 'timeseets.activity_id')
            ->join('project_masters', 'project_masters.id', '=', 'timeseets.project_id')
            ->select('users.name', 'timeseets.id', 'timeseets.start_time', 'timeseets.end_time', 'timeseets.duration', 'timeseets.description', 'timeseets.status', 'timeseets.created_at', 'project_activities.activity_name', 'project_masters.project_name')
            ->orderBy('timeseets.id', 'desc')
            ->whereRaw('date_format(timeseets.created_at,"%m")'."='".$month. "'")
            ->whereRaw('date_format(timeseets.created_at,"%Y")'."='".$request->year. "'")
            ->where('users.id', $request->emp_id)
            ->where('timeseets.orgnization_id', $user_id)->get();


        return response()->json(['data' => $data]);
    }
    public function SaveProjectActivities(Request $request){
        $project = new ProjectActivity();
        $project->project_id = $request->project_id;
        $project->activity_name = $request->activity_name;
        $project->save();
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }
    public function GetActivitiesList($id){
        $data = ProjectActivity::where('project_id',$id)->get();
        return response()->json(['data' => $data]);
    }
    public function DeleteProjectActivities($id){
        ProjectActivity::where('id',$id)->delete();
        return response()->json(['status'=>200,'message'=>'Successfully Deleted']);
    }
    public function GetReporting(Request $request){
        $user_id = Auth::user()->id;
        $data = EmpDetail::select('user_id','first_name','last_name')->where('designation_id',$request->reporting)->where('created_by',$user_id)->orderBy('first_name', 'ASC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function ViewEmpAssignPro(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,a.created_at,b.project_name,CONCAT(c.first_name,' ',c.last_name) as name FROM `emp_projects` as a INNER JOIN project_masters as b on a.project_id=b.id JOIN emp_details AS c on c.user_id=a.employee_id WHERE b.orgnization_id=$user_id AND c.user_id=$request->employee_id GROUP BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function GetFormEngineMasters(){
        $user_id = Auth::user()->id;
        $data = FormEngineCategory::where('orgnization_id',$user_id)->orderBy('name', 'ASC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetOfficeMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.office_name,a.status,a.created_at,a.updated_at,b.name as city,c.name as state FROM `office_masters` AS a INNER JOIN cities AS b ON a.city_id=b.id INNER JOIN states AS c ON c.id=a.state_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        // $data = OfficeMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetShiftMasters(){
        $user_id = Auth::user()->id;
        $data = ShiftMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }

    /*--------VIKAS CODE START HERE-------*/

    public function GetHeaderFooterTemplateMasters(){
       $user_id = Auth::user()->id;  
       $data = DB::select("SELECT a.id,a.orgnization_id,b.office_name,a.header_image,a.footer_image,a.status,a.created_at,a.updated_at FROM `header_footer_template_masters` AS a INNER JOIN `office_masters` AS b ON a.office_id=b.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    } 
 
    public function ViewHeaderFooterData(Request $request){
       $data = DB::select("SELECT a.id,a.orgnization_id,b.office_name,a.header_image,a.footer_image,a.status,a.created_at,a.updated_at FROM `header_footer_template_masters` AS a INNER JOIN `office_masters` AS b ON a.office_id=b.id WHERE a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetStatusHeaderFooterTemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = HeaderFooterTemplateMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }


        public function GetStatusFlowData(Request $request){ 
        $user_id = Auth::user()->id;
        $data = FlowMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }




    /*--------VIKAS CODE END HERE-------*/

    public function GetEmployeeLeaveData(Request $request){
        $status='';
        $user='';
        $department='';
        if(!empty($request->department_id)){
            $department = "AND a.department_id=$request->department_id";
        }if(!empty($request->status)){
            $status = "AND a.status='$request->status'";
        }
        if(!empty($request->user_id)){
            $user = "AND a.user_id=$request->user_id";
        }
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.status,b.employee_code,c.name,d.name as leave_type,a.created_at FROM `leaves` as a INNER JOIN employee_infos as b on a.user_id=b.user_id INNER JOIN users as c on c.id=b.user_id INNER JOIN leave_types as d on d.id=a.leave_type WHERE a.office_id=$request->office_id $user $department $status AND MONTH(a.created_at)='$request->month' AND YEAR(a.created_at)='$request->year' GROUP BY a.id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmpApprovedLeaveData(Request $request){
        $data = DB::select("SELECT a.id,a.user_id,a.duration,a.status,a.created_at,b.name,c.name AS leave_type From leaves AS a INNER JOIN users AS b ON a.user_id=b.id INNER JOIN leave_types AS c on c.id=a.leave_type WHERE a.status='Approved' and a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmpRejectLeaveData(Request $request){
        $data = DB::select("SELECT a.id,a.user_id,a.duration,a.status,a.created_at,b.name,c.name AS leave_type From leaves AS a INNER JOIN users AS b ON a.user_id=b.id INNER JOIN leave_types AS c on c.id=a.leave_type WHERE a.status='Reject' and a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function ViewEmpLeaveData(Request $request){
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.created_at,a.status,b.name as leave_type,c.name,c.mobile FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id INNER JOIN users as c on c.id=a.user_id WHERE a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetLeaveMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.total_leave,a.name AS leaveName,a.id,b.office_name,c.department_name,d.emp_type FROM `leave_types` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id INNER JOIN emp_types AS d ON d.id=a.emp_type WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetListLeave(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.user_id,a.duration,a.status,a.created_at,a.start_date,a.end_date,a.reason_for_leav_comp,b.name,c.name AS leave_type From leaves AS a INNER JOIN users AS b ON a.user_id=b.id INNER JOIN leave_types AS c on c.id=a.leave_type");
        return response()->json(['status'=>200,'data' => $data]);
    }
    
    public function GetParentDepartment($id){
        $user_id = Auth::user()->id;
        $select = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->where('parent_id',0)->where('type_of_department','>',0)->count();
        if($select!=0){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$id)->where('type_of_department',0)->get();
        }else{
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$id)->get();
            
        }
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetParentPosition($ofice_id,$department_id){
        $user_id = Auth::user()->id;
        $select = PositionMaster::where('orgnization_id',$user_id)->where('office_id',$ofice_id)->where('department_id',$department_id)->where('parent_id',0)->where('type_of_position','>',0)->count();
        if($select!=0){
            $data = PositionMaster::select('id','position_name')->where('orgnization_id',$user_id)->where('office_id',$ofice_id)->where('department_id',$department_id)->where('type_of_position',0)->get();
        }else{
            $data = PositionMaster::select('id','position_name')->where('orgnization_id',$user_id)->where('office_id',$ofice_id)->where('department_id',$department_id)->get();
        }
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    // public function GetParentDepartment($id){
    //     $user_id = Auth::user()->id;
    //     $select = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->count();
    //     if($select>1){
    //         $data = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->where('department_id','!=',0)->get();
    //     }else{
    //         $data = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->get();
    //     }
    //     if(!empty($data)){
    //         return response()->json(['status'=>200,'data' => $data]);
    //     }else{
    //         return response()->json(['status'=>400,'data' => []]);
    //     }
    // }
    public function GetStatusDepartment(Request $request){
        $user_id = Auth::user()->id;
        $data = DepartmentMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusOffice(Request $request){
        $user_id = Auth::user()->id;
        $data = OfficeMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusPosition(Request $request){
        $user_id = Auth::user()->id;
        $data = PositionMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusNotice(Request $request){
        $user_id = Auth::user()->id;
        $data = NoticeMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetDefaultNotice(Request $request){
        $user_id = Auth::user()->id;
        $data = NoticeMaster::select('id','is_default')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->is_default = $request->is_default;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetDefaultEducation(Request $request){
        $user_id = Auth::user()->id;
        $data = EducationMaster::select('id','is_default')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->is_default = $request->is_default;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusForm(Request $request){
        $user_id = Auth::user()->id;
        $data = FormEngineCategory::select('id','is_multiple')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->is_multiple = $request->is_multiple;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusEducation(Request $request){
        $user_id = Auth::user()->id;
        $data = EducationMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function ViewDepartmentData(Request $request){
        $data = DB::select("SELECT a.id,a.department_name,a.status,a.created_at,a.updated_at,b.office_name FROM `department_masters` AS a INNER JOIN office_masters AS b ON a.office_id=b.id where a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function ViewOfficeData(Request $request){

        $data = DB::select("SELECT a.status,a.pincode,a.office_name,a.address,c.name AS countryName,d.name AS stateName,e.name AS cityName FROM office_masters AS a INNER JOIN countries AS c ON a.country_id=c.id INNER JOIN states AS d ON a.state_id=d.id INNER JOIN cities AS e ON a.city_id=e.id where a.id=$request->id");

        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function ViewJobDetails(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.job_title,a.no_of_vacancy,a.minimum_salary,a.maximum_salary,a.job_type,a.description,b.office_name,c.department_name,d.position_name,b.address FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.orgnization_id=$user_id and a.id=$request->id");

        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetJobTitle(Request $request){
        $user_id = Auth::user()->id;
        $data = ResourceRequirement::select(['id','job_title'])->where(['orgnization_id'=>$user_id])->orderBy('job_title', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function FetchRequirementDetails(Request $request){
        $user_id = Auth::user()->id;
        $query ='';
        if(!empty($request->job_title)){
            $query .="AND a.job_title='$request->job_title'";
        }
        if(!empty($request->minimum_salary)){
            $query .="AND a.minimum_salary>='$request->minimum_salary'";
        }
        if(!empty($request->maximum_salary)){
            $query .="AND a.maximum_salary<='$request->maximum_salary'";
        }
        $count = DB::select("SELECT COUNT(a.id) as id FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.orgnization_id=$user_id")[0];
        $requirement = DB::select("SELECT a.id,a.job_title,a.no_of_vacancy,a.minimum_salary,a.maximum_salary,a.job_type,a.description,b.office_name,c.department_name,d.position_name,b.address FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.orgnization_id=$user_id $query ORDER by a.id DESC limit 5 OFFSET $request->offset");
        return response()->json(['data' => $requirement,'count'=>round($count->id/2)]);
    }
    public function GetDepartmentName(Request $request){
        $user_id = Auth::user()->id;
        $departmentId=$request->department_id;
        if($departmentId=='0'){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->get();
            //echo "<pre>"; print_r($data); echo "</pre>"; die;
        }
        else {
        $data = DepartmentMaster::select('id','department_name')->where('office_id',$departmentId)->where('orgnization_id',$user_id)->get();
             //echo "<pre>"; print_r($data); echo "</pre>"; 
        }

        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }


    public function GetDesignation(Request $request){ 
        $officeId=$request->office_id;
        $departmentId=$request->department_id;
        if($officeId=='0' and $departmentId =='0'){
        $data = PositionMaster::select('id','position_name')->get();
        }
        else {
        $data = PositionMaster::select('id','position_name')->where('office_id',$officeId)->where('department_id',$departmentId)->get();
        }

        //echo "<pre>"; print_r($data); echo "</pre>"; die;

        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function searchEmpName()
    {
        if(!empty($_POST['search'])){
            $search_name=$_POST['search'];
            $result=DB::select("SELECT id,name FROM `users` WHERE type='2' AND name like '%$search_name%' ORDER BY name ASC");
            if(!empty($result)){
                foreach($result as $row){
                    $datas[]=$row;
                }
                echo json_encode($datas);
            }
        }
    }
    public function GetEmpTypeMaster(Request $request){ 
        $user_id = Auth::user()->id;
        //$data = EmpType::select('id','emp_type','created_at','updated_at')->where('orgnization_id',$user_id)->get();a
        $data = DB::select("SELECT a.id,a.emp_type,b.office_name,a.created_at,a.updated_at FROM `emp_types` as a INNER JOIN `office_masters` as b on a.office_id=b.id WHERE a.orgnization_id=$user_id");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }


    }
    public function GetStatusSourceMasters(Request $request){
        $user_id = Auth::user()->id;
        $data = SourceMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusProject(Request $request){
        $user_id = Auth::user()->id;
        $data = ProjectMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    
    public function GetStatusVander(Request $request){
        $user_id = Auth::user()->id;
        $data = Vander::select('id', 'status')->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }

    public function getVanderStaffStatus(Request $request){
        $user_id = Auth::user()->id;
        $data = VanderStaff::select('id', 'status')->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    
    

    public function GetAssignTask(Request $request){
        $user_id = Auth::user()->id;

        $data = DB::select("SELECT a.id,a.status,a.message,b.project_name,b.start_date,b.end_date,c.activity_name FROM `assign_tasks` AS a INNER JOIN project_masters AS b ON a.project_id=b.id INNER JOIN project_activities AS c ON a.activity_id=c.id WHERE a.orgnization_id=$user_id");
       

        // $data = DB::select("SELECT a.id,a.status,a.message,c.project_name,c.start_date,c.end_date,GROUP_CONCAT(b.activity_name ORDER BY b.id) activity_name FROM assign_tasks a INNER JOIN project_activities b ON FIND_IN_SET(b.id, a.activity_id) > 0 INNER JOIN project_masters c ON FIND_IN_SET(c.id, a.project_id) > 0 WHERE a.orgnization_id=$user_id");

        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetTaskByDepartment(Request $request){
        $user_id = Auth::user()->id;
        $data = ProjectMaster::select(['id','project_name'])->where(['office_id'=>$request->office_id])->where(['department_id'=>$request->department_id])->get();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetAssignActivity(Request $request){
        $data = ProjectActivity::select(['id','activity_name'])->where(['project_id'=>$request->project_id])->orderBy('activity_name', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetShiftType(Request $request)
    {
        $select = WeekDay::get();
        if($request->type=='Daily'){ ?>
            <div class="col-sm-12 mt-4">
                <div class="form-group">
                    <h5 class="shift-ty header_change">Daily Shift Details, Duration: 9.0 Hrs, Break Duration: 0.0 Min</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Type of Shift*</label>
                            <div class="d-flex">
                                <label class="w-50"><input type="radio" name="type_of_shift1[]" class="mx-1" value="Day Shift"> Day Shift</label>
                                <label class="w-50"><input type="radio" class="mx-1" name="type_of_shift1[]" value="Night Shift"> Night Shift</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Continuous Double Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="continuous_double_shift[]" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Variable Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="variable_shift[]" value="1" type="checkbox" class="variable_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>In Time*</label>
                            <input type="time" class="form-control in_time" name="in_time" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Out Time*</label>
                            <input type="time" class="form-control out_time" name="out_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Break Start Time*</label>
                            <input type="time" class="form-control break_start_time" name="break_start_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Break End Time*</label>
                            <input type="time" class="form-control break_end_time" name="break_end_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>In Time Relaxation*</label>
                            <input type="time" class="form-control in_time_relaxation" name="in_time_relaxation[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Out Time Relaxation*</label>
                            <input type="time" class="form-control out_time_relaxation"" name="out_time_relaxation[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Minimum Present Duration (min)*</label>
                            <input type="number" class="form-control minimum_pres_dur" name="min_present_duration[]" onkeyup="CheckMinimumPresent(this.value)" required>
                            <label class="text-info minimum-half-time-duration" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration"> 0</span></strong></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Enable Half Day</label>
                            <div class="form-check"><label class="switch">
                                <input name="enable_half_day[]" class="enable_half_day" onchange="HalfDayEnabled()" value="1" type="checkbox"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }elseif($request->type=='Flexible'){ ?>
            <div class="col-sm-12 mt-4">
                <div class="form-group">
                    <h5 class="shift-ty header_change">Flexible Shift Details</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Type of Shift*</label>
                            <div class="d-flex">
                                <label class="w-50"><input type="radio" name="type_of_shift1" class="mx-1" onclick="flexible_shift(1)" value="Day Shift"> Day Shift</label>
                                <label class="w-50"><input type="radio" class="mx-1" onclick="flexible_shift(2)" name="type_of_shift1" value="Night Shift"> Night Shift</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Continuous Double Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="continuous_double_shift" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Shift Duration (min)*</label>
                            <input type="number" class="form-control shift_duration" placeholder="Enter Shift Durations" name="shift_duration" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Time*</label>
                            <input type="time" class="form-control out_time" name="out_time" value="00:00" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Minimum Present Duration (min)*</label>
                            <input type="number" class="form-control minimum_pres_dur" name="min_present_duration" onkeyup="CheckMinimumPresent(this.value)" required>
                            <label class="text-info minimum-half-time-duration" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration"> 0</span></strong></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Enable Half Day</label>
                            <div class="form-check"><label class="switch">
                                <input name="enable_half_day" class="enable_half_day" onchange="HalfDayEnabled()" value="1" type="checkbox"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function flexible_shift(num){
                    if(num==1){
                        $('.out_time').attr('readonly',true);
                    }else{
                        $('.out_time').attr('readonly',false);
                        $('.out_time').val();
                    }
                }
            </script>
        <?php }elseif($request->type=='Weekly'){
            if(!empty($select)){
                foreach($select as $row){ ?>
                    <div class="col-sm-12 mt-4">
                        <div class="form-group">
                            <h5 class="shift-ty header_change"><?=$row->name;?> Shift Details, Duration: 9.0 Hrs, Break Duration: 0.0 Min</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Type of Shift*</label>
                                    <div class="d-flex">
                                        <label class="w-50"><input type="radio" name="type_of_shift<?=$row->id;?>[]" class="mx-1" value="Day Shift"> Day Shift</label>
                                        <label class="w-50"><input type="radio" class="mx-1" name="type_of_shift<?=$row->id;?>[]" value="Night Shift"> Night Shift</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3"></div>
                            <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Continuous Double Shift</label>
                                    <div class="form-check"><label class="switch">
                                        <input name="continuous_double_shift[]" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Variable Shift</label>
                                    <div class="form-check"><label class="switch">
                                        <input name="variable_shift[]" value="1" type="checkbox" class="variable_shift"><span class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>In Time*</label>
                                    <input type="time" class="form-control in_time" name="in_time[]" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Out Time*</label>
                                    <input type="time" class="form-control out_time" name="out_time[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Break Start Time*</label>
                                    <input type="time" class="form-control break_start_time" name="break_start_time[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Break End Time*</label>
                                    <input type="time" class="form-control break_end_time" name="break_end_time[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>In Time Relaxation*</label>
                                    <input type="time" class="form-control in_time_relaxation" name="in_time_relaxation[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Out Time Relaxation*</label>
                                    <input type="time" class="form-control out_time_relaxation"" name="out_time_relaxation[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Minimum Present Duration (min)*</label>
                                    <input type="number" class="form-control minimum_pres_dur<?=$row->id;?>" name="min_present_duration[]" required>
                                    <label class="text-info minimum-half-time-duration<?=$row->id;?>" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration<?=$row->id;?>"> 0</span></strong></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Enable Half Day</label>
                                    <div class="form-check"><label class="switch">
                                        <input name="enable_half_day[]" class="enable_half_day<?=$row->id;?>" type="checkbox"><span class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(".minimum_pres_dur<?=$row->id;?>").keyup(function(e){
                            $('.half-time-duration<?=$row->id;?>').text($(".minimum_pres_dur<?=$row->id;?>").val());
                        });
                        $(".enable_half_day<?=$row->id;?>").change(function(e){
                            if ($('.enable_half_day<?=$row->id;?>').is(':checked')) {
                                $('.minimum-half-time-duration<?=$row->id;?>').show();
                            }else{
                                $('.minimum-half-time-duration<?=$row->id;?>').hide();
                            }
                        });
                    </script>
                <?php }
            }
        }
    }
    public function GetEmployeeByDepartment(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT b.id,a.employee_code,b.name FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE a.department_id=$request->department_id AND a.organisation_id=$user_id AND a.office_id=$request->office_id GROUP BY b.id");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetLeaveType(Request $request){
        $user_id = Auth::user()->id;
        $date=date('Y-m-d');
        $data = LeaveType::select('id','name','total_leave')->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->where('office_id',$request->office_id)->get();
        $allldatas=array();
        foreach($data as $rows){
            $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$request->user_id AND YEAR(created_at)='$date' LIMIT 1");
            if(!empty($select[0]->leave_type)){
                $rows->totalleave = $rows->total_leave - $select[0]->leave_type;
            }else{
                $rows->totalleave = $rows->total_leave;
            }
            $allldatas[] = $rows;
        }
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $allldatas]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetEmployeeByPosition(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT b.id,b.name,a.employee_code FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code IS NOT null AND a.organisation_id=$user_id AND a.office_id=$request->office_id AND a.department_id=$request->department_id AND a.position_id=$request->position_id");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetLeave(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.total_leave,b.emp_type FROM `leave_types` as a INNER JOIN emp_types as b on a.emp_type=b.id WHERE a.orgnization_id=$user_id AND a.office_id=$request->office_id AND a.department_id=$request->department_id ORDER BY a.name ASC");
        //LeaveType::select('id','name','total_leave')->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->where('office_id',$request->office_id)->get();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }


        public function GetFlowRecords(Request $request){ 
       // echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $user_id = Auth::user()->id;
        $data = FlowMaster::where('id',$request->flow_id)->where('orgnization_id',$user_id)->first();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    } 





    
    public function GetLeaveFlow(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }

/*----==========START SAVE FLOW NAME=========-------*/
     public function SaveFlowName(Request $request){
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('flow_name',$request->flow_name)->where('orgnization_id',$user_id)->first();
        if(!empty($select)){
            $approval = ApprovalFlow::select('office_id')->where('flow_id',$select->id)->first();
            if(!empty($approval)){
                $office = OfficeMaster::select('id','office_name')->where('id',$approval->office_id)->first();
            }else{
                $office=[];
            }

            $result = DB::select("SELECT a.id,a.flow_id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$select->id ORDER BY a.id DESC");

            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$select->id AND a.orgnization_id=$user_id");
            $html='<div class="alert alert-info">
    <strong>SORRY!</strong> Already added this flow.<br><b>Try with different flow name.</b></div>';
            $msg="Duplicate Entry";

            //$html;
            return response()->json(['status'=>400,'msg'=>$msg,]);
        }else{
            $flow_master = new FlowMaster();
            $flow_master->flow_name = $request->flow_name;
            $flow_master->orgnization_id = $user_id;
            $flow_master->save();
            $approval = ApprovalFlow::select('office_id')->where('flow_id',$flow_master->id)->first();
            if(!empty($approval)){
                $office = OfficeMaster::select('id','office_name')->where('id',$approval->office_id)->first();
            }else{
                $office=[];
            }
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$flow_master->id ORDER BY a.id DESC");
            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$flow_master->id AND a.orgnization_id=$user_id");

            /*echo "<pre>"; print_r($flow_master); echo "</pre>";
            echo "</br>";
            echo "<pre>"; print_r($result); die;*/
            return response()->json(['status'=>200,'msg'=>'Added successfully','flow'=>$flow_master,'datas'=>$result,'authorities'=>$datas,'office'=>$office]);
        }
    }
/*----==========END SAVE FLOW NAME=========-------*/

    public function SaveApprovalFlow(Request $request){

        $flow_id=$request->flow_id;
        //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $user_id = Auth::user()->id;
        $select = ApprovalFlow::select('id','office_id','flow_id')->where('flow_id',$request->flow_id)->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('position_id',$request->position_id)->where('leave_type',$request->leave_type)->first();

        if(empty($select->id)){
            $approval_flow = new ApprovalFlow();
            $approval_flow->orgnization_id = $user_id;
            $approval_flow->flow_id = $request->flow_id;
            $approval_flow->office_id = $request->office_id;
            $approval_flow->department_id = $request->department_id;
            $approval_flow->position_id = $request->position_id;
            $approval_flow->leave_type = $request->leave_type;
            $approval_flow->save();
            $office = OfficeMaster::select('id','office_name')->where('id',$request->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,g.id as flow_id,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND g.id=$flow_id AND a.flow_id=$approval_flow->flow_id ORDER BY a.id DESC");

            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$result,'office'=>$office]);
        }else{
            $office = OfficeMaster::select('id','office_name')->where('id',$select->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND g.id=$flow_id AND a.flow_id=$select->flow_id");
               echo "<pre>"; print_r($result); echo "</pre>"; die;

                return response()->json(['status'=>400,'msg'=>'Already added this flow','datas'=>$result,'office'=>$office]);
        }
    }

    /*-------START NEW SAVE ALL APPROVAL FLOW------*/
      public function SaveAllApprovalFlow(Request $request){ 
        //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $user_id = Auth::user()->id;
        $select = ApprovalFlow::select('id','office_id')->where('flow_id',$request->flow_id)->where('office_id',$request->office_id)->first();
        if(empty($select->id)){
            $approval_flow = new ApprovalFlow();
            $approval_flow->orgnization_id = $user_id;
            $approval_flow->flow_id = $request->flow_id;
            $approval_flow->office_id = $request->office_id;
            $approval_flow->department_id = '0';
            $approval_flow->position_id = '0';
            $approval_flow->leave_type = '0';
            $approval_flow->save();
            $office = OfficeMaster::select('id','office_name')->where('id',$request->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$approval_flow->flow_id ORDER BY a.id DESC");
            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$result,'office'=>$office]);
        }else{
            $office = OfficeMaster::select('id','office_name')->where('id',$select->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$select->flow_id ORDER BY a.id DESC");
                return response()->json(['status'=>400,'msg'=>'Already added this flow','datas'=>$result,'office'=>$office]);
        }
    }


    

    /*-------END NEW SAVE ALL APPROVAL FLOW------*/
 public function SaveAuthorityAdmin(Request $request){
        $user_id = Auth::user()->id;
        $office_id = $request->office_id;
        $flow_id = $request->flow_id;
       // echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $select = LeaveAuthority::select('id')->where([
            'flow_id'=>$request->flow_id,
            'orgnization_id'=>$user_id,
            'office_id'=>$request->office_id,
            'department_id'=>$request->department_id,
            'position_id'=>$request->position_id,
            'user_id'=>$request->authority_user,
        ])->first();
        
        if($office_id=='0') {
            $records = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->flow_id  AND a.orgnization_id=$user_id");



        if(!empty($records)) {
            return response()->json(['status'=>400,'msg'=>'Already added this approval','datas'=>$records]);
            } else{

            $leave_authority = new LeaveAuthority();
            $leave_authority->flow_id = $request->flow_id;
            $leave_authority->orgnization_id = $user_id;
            $leave_authority->office_id = $request->office_id;
            $leave_authority->save();

            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->flow_id  AND a.orgnization_id=$user_id");
            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$datas]);

            }

        }

        else if(empty($select)){
            $leave_authority = new LeaveAuthority();
            $leave_authority->flow_id = $request->flow_id;
            $leave_authority->orgnization_id = $user_id;
            $leave_authority->office_id = $request->office_id;
            $leave_authority->department_id = $request->department_id;
            $leave_authority->position_id = $request->position_id;
            $leave_authority->user_id = $request->authority_user;
            $leave_authority->save();
            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->flow_id AND a.orgnization_id=$user_id");
            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$datas]);
             //return redirect('add-approval-flow')->with('success','Added Successfully');


        }else{
            return response()->json(['status'=>400,'msg'=>'Already added this approval','datas'=>[]]);
        }
    }

    public function SaveSettings(Request $request){
        $user_id = Auth::user()->id;

        //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;

        $select = NotificationSetting::where('flow_id',$request->flow_id)->where('flow_type','leave-flow')->first();
        FlowMaster::where('id',$request->flow_id)->update(['is_complete'=>1]);
        if(!empty($select)){
            $select->flow_id = $request->flow_id;
            $select->flow_type = 'leave-flow';
            $select->orgnization_id = $user_id;
            $select->email_for_approve = !empty($request->email_for_approve) ? 1:0;
            $select->email_for_reject = !empty($request->email_for_reject) ? 1:0;
            $select->sms_for_approve = !empty($request->sms_for_approve) ? 1:0;
            $select->sms_for_reject = !empty($request->sms_for_reject) ? 1:0;
            $select->app_for_approve = !empty($request->app_for_approve) ? 1:0;
            $select->app_for_reject = !empty($request->app_for_reject) ? 1:0;
            $select->save();
            return response()->json(['status'=>200,'msg'=>'Updated setting','datas'=>$select]);
        }else{
            $setting  = new NotificationSetting();
            $setting->flow_id = $request->flow_id;
            $setting->flow_type = 'leave-flow';
            $setting->orgnization_id = $user_id;
            $setting->email_for_approve = !empty($request->email_for_approve) ? 1:0;
            $setting->email_for_reject = !empty($request->email_for_reject) ? 1:0;
            $setting->sms_for_approve = !empty($request->sms_for_approve) ? 1:0;
            $setting->sms_for_reject = !empty($request->sms_for_reject) ? 1:0;
            $setting->app_for_approve = !empty($request->app_for_approve) ? 1:0;
            $setting->app_for_reject = !empty($request->app_for_reject) ? 1:0;
            $setting->save();
            return response()->json(['status'=>200,'msg'=>'Saved setting','datas'=>$setting]);
        }
    }

   public function GetFlowData(Request $request){
        $user_id = Auth::user()->id;
        $data = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->first();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    } 



     public function GetRootFlowData(Request $request){
        $flow_id=$request->id;
        $user_id = Auth::user()->id;
        $data = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->get();
        
        if(!empty($data)){

        $leave_flow = DB::select("SELECT a.id,g.flow_name,g.id as flow_id,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND g.id=$flow_id ORDER BY a.id ASC");
       // echo "<pre>"; print_r($leave_flow); echo "</pre>"; die;

        $authority_flow = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id AND b.id=$flow_id AND a.orgnization_id=$user_id ORDER BY a.id ASC");
       $selectNotification = NotificationSetting::where('flow_id',$flow_id)->where('orgnization_id',$user_id)->where('flow_type','leave-flow')->first(); 

       // echo "<pre>"; print_r($selectNotification); echo "</pre>";  

         if(!empty($selectNotification)){
            $email_for_approve=$selectNotification->email_for_approve;
            $email_for_reject=$selectNotification->email_for_reject;
            $sms_for_approve=$selectNotification->sms_for_approve;
            $sms_for_reject=$selectNotification->sms_for_reject;
            $app_for_approve=$selectNotification->app_for_approve;
            $app_for_reject=$selectNotification->app_for_reject;

            if($email_for_approve=='1'){
                $email_approve='Yes';
            }else{
                $email_approve='No';
            }

            if($email_for_reject=='1'){
                $email_reject='Yes';
            }else{
                $email_reject='No';
            }
            /*-----SMS-------*/

            if($sms_for_approve=='1'){
                $sms_approve='Yes';
            }else{
                $sms_approve='No';
            }

            if($sms_for_reject=='1'){
                $sms_reject='Yes';
            }else{
                $sms_reject='No';
            }
            /*------APP------*/

            if($app_for_approve=='1'){
                $app_approve='Yes';
            }else{
                $app_approve='No';
            }
            if($app_for_reject=='1'){
                $app_reject='Yes';
            }else{
                $app_reject='No';
            }

         }
         else{
            $email_approve='No';
            $email_reject='No';
            $sms_approve='No';
            $sms_reject='No';
            $app_approve='No';
            $app_reject='No';
         }
        $setting_notification=array(['email_for_approve'=>$email_approve,'email_for_reject'=>$email_reject,'sms_for_approve'=>$sms_approve,'sms_for_reject'=>$sms_reject,'app_for_approve'=>$app_approve,'app_for_reject'=>$app_reject]);


            return response()->json(['status'=>200,'data'=>$leave_flow, 'authority_flow'=>$authority_flow,'notification'=>$setting_notification]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }

    } 




    
    public function GetLeaveFlowData(Request $request){
       // echo "<pre>"; print_r($request->all());  echo "<pre>"; die;
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->first();
        $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$request->id ORDER BY a.id DESC");

        $datas = DB::select("SELECT a.id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->id AND a.orgnization_id=$user_id");

      // echo "<pre>"; print_r($result);  echo "<pre>"; 
      // echo "<pre>"; print_r($datas);  echo "<pre>"; 


       $selectNotification = NotificationSetting::where('flow_id',$request->id)->where('orgnization_id',$user_id)->where('flow_type','leave-flow')->first();

       //echo "<pre>"; print_r($selectNotification); echo "</pre>"; die;
         if(!empty($selectNotification)){
           $email_for_approve=$selectNotification->email_for_approve;
           $email_for_reject=$selectNotification->email_for_reject;
            $sms_for_approve=$selectNotification->sms_for_approve;
            $sms_for_reject=$selectNotification->sms_for_reject;

            $app_for_approve=$selectNotification->app_for_approve;
            $app_for_reject=$selectNotification->app_for_reject;

            if($email_for_approve=='1'){
                $email_approve='Yes';
            }else{
                $email_approve='No';
            }

            if($email_for_reject=='1'){
                $email_reject='Yes';
            }else{
                $email_reject='No';
            }
            /*-----SMS-------*/

            if($sms_for_approve=='1'){
                $sms_approve='Yes';
            }else{
                $sms_approve='No';
            }

            if($sms_for_reject=='1'){
                $sms_reject='Yes';
            }else{
                $sms_reject='No';
            }
            /*------APP------*/

            if($app_for_approve=='1'){
                $app_approve='Yes';
            }else{
                $app_approve='No';
            }
            if($app_for_reject=='1'){
                $app_reject='Yes';
            }else{
                $app_reject='No';
            }

         }
         else{
            $email_approve='No';
            $email_reject='No';
            $sms_approve='No';
            $sms_reject='No';
            $app_approve='No';
            $app_reject='No';
         }
        $setting_notofication=array(['email_for_approve'=>$email_approve,'email_for_reject'=>$email_reject,'sms_for_approve'=>$sms_approve,'sms_for_reject'=>$sms_reject,'app_for_approve'=>$app_approve,'app_for_reject'=>$app_reject]);
         //echo "<pre>"; print_r($setting_notofication);  echo "<pre>"; die;

        return response()->json(['status'=>200,'msg'=>'Succefully fetach','flow'=>$select,'datas'=>$result,'authorities'=>$datas, 'notification'=>$setting_notofication,]);
    }

    /*-----------START GET LEAVE APPROVAL FLOW DATA-----------------*/
     public function GetLeaveApprovalFlowData(Request $request){ 
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->first();
        $result = DB::select("SELECT a.id,g.flow_name,g.created_at,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$request->id ORDER BY a.id DESC");


        $datas = DB::select("SELECT a.id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->id AND a.orgnization_id=$user_id");

        return response()->json(['status'=>200,'msg'=>'Succefully fetach','flow'=>$select,'datas'=>$result,'authorities'=>$datas]);
    }
    /*-----------END GET LEAVE APPROVAL FLOW DATA-----------------*/


/*-----------START GET AUTHORITY APPROVAL FLOW DATA-----------------*/
      public function GetAuthorityApprovalFlowData(Request $request){ 
     $requestData=explode(',', $request->id);
       $flowid=$requestData[0];
       $ids=$requestData[1];
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('id',$flowid)->where('orgnization_id',$user_id)->first();
        $result = DB::select("SELECT a.id,g.flow_name,g.created_at,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.id=$ids AND a.flow_id=$flowid ORDER BY a.id DESC");


        $datas = DB::select("SELECT a.id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.id=$ids AND a.flow_id=$flowid AND a.orgnization_id=$user_id");
    
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','flow'=>$select,'datas'=>$result,'authorities'=>$datas]);
    }

/*-----------END GET AUTHORITY APPROVAL FLOW DATA-----------------*/








    public function SendPushNotification($data,$users,$user_type=0){
        $url = "https://fcm.googleapis.com/fcm/send";
        $subscription_key  = "key=AAAAG7wrjHo:APA91bH4jiRhFeKIJH162DXswTxQj5lqfl3Pv98UcEzE6k4AkjAn-u6P-mEyoWEqiEV6epMeNCiWieIFiO3Fc4fNQLkt7vH_CX8Ki59Gr-uKzCixQdxoKA8vhOvwqTHo-oGTG-Pdf8TL";
        $request_headers = array(
            "Authorization:" . $subscription_key,
            "Content-Type: application/json"
        );
        $postRequest = [
            "notification"=>[
                "title"     =>$data['title'],
                "body"      =>$data['body'],
                "icon"      =>"https://lnxx-hrms.sspl20.com/organization/logo/lnxxx.png",
                "click_action"=>"https://lnxx-hrms.sspl20.com/"
            ],
            "to"=>$users->fcm_id
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postRequest));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        $season_data = curl_exec($ch);
        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch);
            exit();
        }
        curl_close($ch);
        $datas['user_id']       =$users->id;
        $datas['title']         =$data['title'];
        $datas['description']   =$data['body'];
        $datas['msg_status']    =$season_data;
        $datas['created_at']    =date('Y-m-d H:i:s');
        $datas['user_type']     =$user_type;
        DB::table('notifications_history')->insert($datas);
    }
    public function StoreToken(Request $request){
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id)->first();
        $user->fcm_id = $request->token;
        $user->save();
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$user]);
    }
    public function OTPSend(){
        return view('otp');
    }
    public function HiringProcessStatus($id){
        $status = DB::select("SELECT a.id,b.name,a.user_id,a.status_for,a.status,a.status_remark,a.created_at FROM `all_status` as a INNER JOIN users as b on a.orgnization_id=b.id WHERE a.user_id=$id AND status_for='hiring_process' ORDER BY a.id DESC");
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$status]);
    }
    public function GetMeetingLinkdata(Request $request){
        $history = InterviewHistory::where('id',$request->id)->first();
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$history]);
    }
    public function UploadStatusDocument(Request $request){
        $user_id = Auth::user()->id;
        $input=$request->all();
        $images=array();
        if($files=$request->file('upload_document')){
            $sr=0;
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $file->move('public/uploads/status_document',$name);
                $status = new InterviewDocument();
                $status->orgnization_id = $user_id;
                $status->candidate_id = $request->candidate_id;
                $status->document_id = $request->document_id;
                $status->documnet_title = $request->filename[$sr++];
                $status->documnet_file = $name;
                $status->save();
                $status->createdat = date_format(date_create($status->created_at),"d-M-Y H:i");
                
            }
        }
        $hiring_approval = HiringApproval::select('employee_id')->where('organisation_id',$user_id)->where('status_id',$request->document_id)->first();
        if(!empty($hiring_approval)){
            $empt = EmpDetail::select('salutation','first_name','middle_name','last_name')->where('id',$request->candidate_id)->first();
            $hiring_sta = InterviewHiringStatu::select('id','status_name')->where('orgnization_id',$user_id)->where('id',$request->document_id)->first();
            $users_mail = User::select('id','name','email','fcm_id')->whereIn('id',explode(",",$hiring_approval->employee_id))->get();
            foreach($users_mail as $row){
                $body = 'Dear '.$row->name.' '.$hiring_sta->status_name.' varification for '.$empt->salutation.' '.$empt->first_name.' '.$empt->middle_name.' '.$empt->last_name.' please check';
                if(!empty($row->fcm_id)){
                    $not['title']='Approval '.$hiring_sta->status_name;
                    $not['body']= $body;
                    $this->SendPushNotification($not,$row,2);
                }
                $this->SendStatusApprovalMail($row,$empt,$body,$hiring_sta->status_name);
            }
        }
        return response()->json(['status'=>200,'msg'=>'Succefully uploaded','data'=>$status,'document_id'=>$request->document_id]);
    }
    public function SendStatusApprovalMail($data,$empt,$body,$hiring_sta){
        $email = array($data->email, 'naavjot@shailersolutions.com');
        try {
            $template = [
                'emp_name'=> $empt->salutation.' '.$empt->first_name.' '.$empt->middle_name.' '.$empt->last_name,
                'approver_name'=> $data->name,
                'subject'=>$body,
                'status_name'=>$hiring_sta,
                'user_name'=> Auth::user()->name
            ];
            Mail::send(['html'=>'email.status_approval'], $template,
                function ($message) use ($email,$template) {
                    $message->to($email)->from("vikaspyadava@gmail.com")->subject($template['subject']);
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function GetUploadedDocumentStatus(Request $request){
        $user_id = Auth::user()->id;
        $statux = InterviewDocument::select('id','documnet_title','documnet_file')->where('orgnization_id',$user_id)->where('document_id',$request->document_id)->where('candidate_id',$request->candidate_id)->get();
        return response()->json(['status'=>200,'msg'=>'Data Fetch Succefully','data'=>$statux,'document_id'=>$request->document_id]);
    }
    public function RemoveDocumet(Request $request){
        $user_id = Auth::user()->id;
        InterviewDocument::where('id',$request->id)->delete();
        $count = InterviewDocument::select('id','documnet_title','documnet_file')->where('orgnization_id',$user_id)->where('document_id',$request->document_id)->where('candidate_id',$request->candidate_id)->count();
        return response()->json(['status'=>200,'msg'=>'Succefully Removed','count'=>$count]);
    }
    public function EmployeeAgainstUser(Request $request){
        $office = implode(',',$request->office_id);
        $user_id = Auth::user();
        $users = DB::select("SELECT b.id,a.employee_code,b.name,b.email FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code is NOT null AND a.organisation_id=$user_id->id AND office_id in ($office)");
        return response()->json(['status'=>200,'msg'=>'Succefully Fetch Data','users'=>$users]);
    }
    public function UpdateUsersStatus(Request $request){
        $data = User::select('id','status')->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function UpdateOrganizationStatus(Request $request){
        $data = Organisation::where('id', $request->id)->select('id', 'status')->first();
       // dd($data);
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetOrgnisationCategory(Request $request){
        $user_id = Auth::user()->id;
        $data = FormEngineCategory::select('id','name')->where('orgnization_id',$user_id)->get();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }

    /*------------START GET EMP OFFICE BASE DEPARTMENT-------------*/


public function GetEmpOffice(Request $request){ 

        $office_id=$request->id; 
        
        $user_id = Auth::user()->id;
        $select = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$office_id)->where('parent_id',0)->where('type_of_department','>',0)->count();
        if($select!=0){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$office_id)->where('type_of_department',0)->get();
        }else{
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$office_id)->get();
            
        }

        //echo "<pre>"; print_r($data); echo "</pre>"; die;
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }

/*------------END GET EMP OFFICE BASE DEPARTMENT-------------*/

/*------------START GET EMP DEPARTMENT BASE DATA-------------*/


public function GetEmpDepartment(Request $request){
        $user_id = Auth::user()->id;
        $department_id=$request->department_id; 
        $dept_data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('id',$department_id)->first(); 
       $dept_name=$dept_data->department_name; 

       $select = DepartmentMaster::where('orgnization_id',$user_id)->where('id',$department_id)->where('parent_id',0)->where('type_of_department','>',0)->count();


       $position = DB::select("SELECT a.id,a.position_name,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,a.type_of_position,a.sub_position FROM `position_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id WHERE a.orgnization_id=$user_id and a.id=$department_id ORDER BY a.id DESC");

        echo "<pre>"; print_r($position); echo "</pre>"; die;



        if($select!=0){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('id',$department_id)->where('type_of_department',0)->get();
        }else{
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('id',$department_id)->get();
            
        }

      



        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }

/*------------END GET EMP DEPARTMENT BASE DATA-------------*/

/*=============START GET DAILY ATTENDENCE ===================*/

public function GetDailyAttendence(){// echo "vikas"; die;
        $orgnaization = Auth::user()->id;
        $emp_detail = EmpDetail::select('id')->where('created_by',$orgnaization)->get();

        if(!empty($emp_detail)){
            foreach($emp_detail as $row){
                $data[]=$row->id;
            }
            $id = implode(',',$data);
 

            $data = EmpAttendance::select('id','user_id','in_time','total_time','out_time','in_image','out_image','created_at')->whereIn('id', $data)->whereIn('id', $data)->orderBy('id', 'DESC')->get();
            //echo "<pre>"; print_r($data); echo "</pre>"; die;

         }else{
            $data=[];
        }
        
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
        
    }

/*=============END GET DAILY ATTENDENCE  ====================*/


    /*-----------------START UPLOAD OFFER LETTER DOCUMENT------------------*/

    // public function UploadOfferLetterDocument(Request $request){
    //     $user_id = Auth::user()->id;
    //     $candidate_id=$request->candidate_id;
    //     $result = SendHrRequest::where(['organisation_id'=>$user_id,'id'=>$candidate_id,'hiring_status'=>'0'])->first();
    //     $input=$request->all();
    //     $images=array();
    //     if(!empty($result)){
    //         if($files=$request->file('upload_document')){ 
    //         $document_title=array();
    //         $document_file=array();
    //         $sr=0;
    //         $date = date('Y-m-d H:i:s');
    //         foreach($files as $file){

    //             $newfilename = rand(100000, 999999);
    //             $name=$file->getClientOriginalName();
    //             $new_names=$newfilename.$name;
    //             $file->move('public/uploads/upload_offer_letter_document',$new_names);
    //             $saveofferRec = new SendOfferLettersToCandidate();
    //             $document_title[] = $request->filename[$sr++];
    //             $document_file[] =  $new_names;     
    //         }
    //              $get_duplicate = SendOfferLettersToCandidate::where(['organisation_id'=>$user_id,'candidate_id'=>$candidate_id])->first(); 
    //              if(empty($get_duplicate)){                  
    //                 $saveofferCandiateRec = new SendOfferLettersToCandidate();
    //                 $saveofferCandiateRec->organisation_id = $user_id;
    //                 $saveofferCandiateRec->candidate_id = $result->id;
    //                 $saveofferCandiateRec->name = $result->candidate_name;
    //                 $saveofferCandiateRec->email = $result->candidate_email;
    //                 $saveofferCandiateRec->position = $result->candidate_position_id;
    //                 $saveofferCandiateRec->salary = $result->candidate_salary;
    //                 $saveofferCandiateRec->gender = $result->candidate_gender;
    //                 $saveofferCandiateRec->manager_name = $result->manager_name;
    //                 $saveofferCandiateRec->mobile = $result->candidate_mobile;
    //                 $saveofferCandiateRec->document_title= json_encode($document_title); 
    //                 $saveofferCandiateRec->document_file = json_encode($document_file);
    //                 $saveofferCandiateRec->tracking_status  =  '1';
    //                 $saveofferCandiateRec->offer_letter_release_date = $date;
    //                 $data=$saveofferCandiateRec->save(); 
    //                 $msg="Offer Letter Send Successfully.";
    //                 $results = DB::table('send_hr_requests')
    //                 ->where(['organisation_id'=>$user_id,'id'=>$result->id])
    //                 ->update(['hiring_status' => '1',]);
    //               } else {
    //                 $msg="Offer Letter Already Send.";
    //                 $data='';
    //               }         
    //         }

    //     }  

    //      if(!empty($data)){
    //             $updated_rec = SendOfferLettersToCandidate::where(['organisation_id'=>$user_id,'candidate_id'=>$candidate_id,'tracking_status'=>'1'])->first();
    //              if(!empty($updated_rec)){
    //                     $id = $saveofferCandiateRec->id;
    //                     $this->SendHrOfferLetterMail($updated_rec, $id);
    //             }
    //             return response()->json(['status'=>200,'msg'=>$msg,'data' => $data]);
               
    //     }else{  $msg="Offer Letter Already Send.";
    //         return response()->json(['status'=>400,'msg'=>$msg,'data' => $data]);
    //     }
    
    // }

    /*-------------------END UPLOAD OFFER LETTER DOCUMENT------------------*/

    /*-----------------START SEND OFFER LETTER TO CANDIDATE THROUTH MAIL-----------------*/
       public function SendHrOfferLetterMail($data, $id, $attach_ments){
        $user_id = Auth::user()->id;
        $email = array($data->email);
        $letter = SendOfferLettersToCandidate::where('id', $id)->select('document_title', 'document_file')->first();
        $document_titles = json_decode($letter->document_title);
        $document_files = json_decode($letter->document_file);
        $document_title = $document_titles[0];
        $document_file = $document_files[0];
        $token = \encrypt($id);
        $rec_id=$data->id;
        $organisation_id=$data->organisation_id;
        $candidate_id=$data->candidate_id;    
        $positions = PositionMaster::select('position_name')->where(['id'=>$data->position])->first();
        try {
            $template_data = [
                'name'           => $data->name,
                'email'          => $data->email,
                'mobile'         => $data->mobile,
                'position'       => $positions->position_name,
                'document_title' => $data->document_title,
                'document_file'  => json_encode($data->document_file),
                'manager_name'   =>  json_encode($data->manager_name),
                'token'   => $token,
            ];
            $document_file = str_replace(' ', '%20', $document_file);
            $string = $document_file;
            $parts = explode(".", $string);
            $extension = end($parts);
            $document_title = $document_title.'.'.$extension;

            //  Mail::send(['html'=>'email.offer_letter'], $template_data,
            //     function ($message) use ($email,$template_data, $document_file, $document_title) {
            //         $message->to($email)->from("lnxx@gmail.com")->subject($template_data['name'].' '.$template_data['manager_name'])->attachData(public_path('uploads/upload_offer_letter_document'.$document_file.''), $document_title);
            // }); 

            Mail::send(['html'=>'email.offer_letter'], $template_data, function ($message) use ($email, $template_data, $document_file, $document_title, $attach_ments) {
            $message->to($email)
            ->from("lnxx@gmail.com")
            ->subject($template_data['name'].' '.$template_data['manager_name']);
            foreach ($attach_ments as $file) {
            $message->attach($file);  
            }
            // ->attachData(public_path('uploads/upload_offer_letter_document/' . $document_file), $document_title);

            });


            return true;
        } catch (Exception $ex) {
          // dd($ex);
            return false;
        }  
    }

  /*-----------------END SEND OFFER LETTER TO CANDIDATE THROUTH MAIL-----------------*/


    public function generate(){
        return Hash::make('1066@12345');
    }
}
