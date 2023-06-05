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
  color: #007bff;
  text-decoration: none;
  }
  #leave_data td{
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
</style>
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header card-height">
        <div class="row">
          <div id="msg" style="display:none;">
            <div id="alert_msg"> </div>
          </div>
          <div class="col-md-12 col-12">
            <h5 class="" id="getCameraSerialNumbers">Track Hiring Status</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table id="examples" class="display" style="width:100%">
          <thead>
            <tr>
              <th>Sr No.</th>
              <th>Name</th>
              <th>Position</th>
              <th>Candidate Email</th>
              <!-- <th>HR Email</th> -->
              <th>Status</th>
              <th>Last Updated Date</th>
              <th>View Details</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if(!empty($result))
            @foreach($result as $row)
            <tr>
              <td>{{$loop->iteration}}</td>
              <td>
                {{$row->candidate_name}}
             <!--  <a href="{{url('candidate-common-profile-share-link/'.$row->id)}}" target="_blank"> {{$row->candidate_name}} </a> --> </td>
              <td>{{$row->position_name}} </td>
              <td>{{$row->candidate_email}}</td>
             <!--  <td>{{$row->hr_email}}</td> -->
              <td> 

              <?php 
               if($row->hiring_status == 0){ ?>
                Sent by Manager
                <?php } elseif($row->hiring_status == 1) { ?>
                Offer letter Sent
                <?php } elseif($row->hiring_status == 2) { ?>
                Offer Letter Accepted
               <?php } elseif($row->hiring_status == 3) { ?>
                Offer Letter Rejected
               <?php } elseif($row->hiring_status == 4) { ?>
                Waiting for eVisa Approval
              <?php } elseif($row->hiring_status == 5) { ?>
                eVisa Approved 
              <?php } elseif($row->hiring_status == 6) { ?>
                eVisa Rejected
              <?php } elseif($row->hiring_status == 7) { ?>
              <?php
                $sql = DB::table('send_pro_lc_mols')->select('status')->where('candidate_id', $row->id)->first();
            
              if(@$sql->status == 0) { ?>
                Sent to PRO for LC/MOL
            
              <?php } else { ?>
              LC/MOL document uploaded & sent to candidate
              <?php } } elseif($row->hiring_status == 8) { ?>
                LC/MOL signed copy uploaded
           <?php } elseif($row->hiring_status == 9) {
                 
            
                $sql = DB::table('send_pro_evisa_processings')->select('status')->where('candidate_id', $row->id)->first();
              if(@$sql->status == 0) { ?>
                Send to PRO for eVisa Processing
              
              <?php } else { ?>
              eVisa Documents Uploaded
              <?php } } elseif($row->hiring_status == 10) {
        
                $sql = DB::table('medical_appointments')->select('status')->where('candidate_id', $row->id)->first();
              if(@$sql->status == 0) { ?>
                Medical Test Appointment Sent
             <?php } else { ?>
              Medical Reports Uploaded
              <?php }
               } elseif($row->hiring_status == 11) { ?>
               Sent PRO for EID Process
              <?php } elseif($row->hiring_status == 12) { ?>
              EID Uploaded
              <?php } ?>

              </td>
              <td><?php echo date_format(date_create($row->updated_at),"d-M-Y"); ?></td>
              <td> <a href="#" data-toggle="modal" data-target="#myModal" class="text-primary" type="button" onclick="get_candidate_data(<?php echo $row->id; ?>);"><i class="fa fa-eye" style="font-size:25px; margin-left: 25px;"></i> </td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
<?php
  $sign_doc = DB::table('candidate_required_documents')->where('candidate_id', $row->id)->where('document_id', '!=', 6)->count();
?>

                    <a href="#" data-toggle="modal" @if($sign_doc != 0) @else data-target="#UploadRequiredDoc" onclick="upload_required_doc();" @endif class="dropdown-item"> @if($sign_doc != 0) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload required doc</a>
<?php
  $sign_doc = DB::table('candidate_required_documents')->where('candidate_id', $row->id)->where('document_id', 6)->count();
?>
                    <a href="#" @if($sign_doc != 0) @else data-toggle="modal" data-target="#UploadSignedDoc" onclick="upload_signed_doc(<?php echo $row->id; ?>);" @endif class="dropdown-item"> @if($sign_doc != 0) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload  signed doc</a>

                    <a href="#" @if($row->hiring_status > 3) @else data-toggle="modal" data-target="#SendVisaApprovalModal" onclick="send_visa_approval('{{$row->id}}');" @endif class="dropdown-item"> @if($row->hiring_status > 3) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Send for eVisa approval</a>
                    
                    <a href="#" data-toggle="modal" @if($row->hiring_status > 6) @else data-target="#SendProLCModal" onclick="send_to_pro('{{$row->id}}');" @endif   class="dropdown-item"> @if($row->hiring_status > 6) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Send to PRO for LC/MOL Process</a>

<?php
  $sign_doc = DB::table('lc_mol_docs')->where('candidate_id', $row->id)->count();
?>

                    <a href="#" data-toggle="modal" @if($sign_doc != 0) @else data-target="#SendUploadProLCModal" onclick="upload_LC_MOL('{{$row->id}}');" @endif class="dropdown-item">  @if($sign_doc != 0) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload LC/MOL & send to candidate</a>

                    <a href="#" data-toggle="modal" @if($row->hiring_status > 7) @else data-target="#UploadLCMOLModal" onclick="upload_LC_MOL_signed('{{$row->id}}');" @endif  class="dropdown-item"> @if($row->hiring_status > 7) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload LC/MOL signed copy</a>

                    <a href="#" data-toggle="modal" @if($row->hiring_status > 8) @else data-target="#PROeVisaProcessing" onclick="send_PRO_eVisa_processing('{{$row->id}}');" @endif class="dropdown-item"> @if($row->hiring_status > 8) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Send to PRO for eVisa processing</a>

<?php
  $sign_doc = DB::table('visa_documents')->where('candidate_id', $row->id)->count();
?>
                    <a href="#" data-toggle="modal" @if($sign_doc != 0) @else data-target="#UploadeVisaSend" onclick="upload_eVisa_send_to_candidate('{{$row->id}}');" @endif  class="dropdown-item"> @if($sign_doc != 0) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload eVisa and send to candidate</a>

                    <a href="#" data-toggle="modal" @if($row->hiring_status > 9) @else data-target="#MedicalTestAppontment" onclick="send_medical_test_appontment('{{$row->id}}');" @endif  class="dropdown-item"> @if($row->hiring_status > 9) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Send medical test appointment date</a>

<?php
  $sign_doc = DB::table('medical_reports')->where('candidate_id', $row->id)->count();
?>
                    <a href="#" data-toggle="modal" @if($sign_doc != 0) @else data-target="#UploadMedicalReports" onclick="Upload_medical_reports('{{$row->id}}');" @endif  class="dropdown-item"> @if($sign_doc != 0) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload medical reports</a>

                    <a href="#" data-toggle="modal" @if($row->hiring_status > 10) @else data-target="#SendPROEIDProcess" onclick="Send_PRO_EID_process('{{$row->id}}');" @endif  class="dropdown-item"> @if($row->hiring_status > 10) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Send to PRO for EID process</a>

                    <a href="#" data-toggle="modal" @if($row->hiring_status > 11) @else data-target="#UploadEIDcandidate" onclick="Upload_EID_candidate('{{$row->id}}');" @endif class="dropdown-item"> @if($row->hiring_status > 11) <i class="fa fa-check" style="font-size: 14px;color: green;"></i> @else <i class="fa fa-close" style="font-size: 14px;color: red;"></i> @endif Upload EID and send to candidate</a>

              </td>
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
</div>
<!-- ==========START POPUP BOX=========== -->
<!-- ====SHOW CANDIDATE DETAILS DATA===== -->

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title d-flex">
              <div id="candidate_data"></div>&nbsp;&nbsp; <span id="full_name"></span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body" id="reason_for_leav_comp_desc">
            <section style="background-color: #eee;">
                <div class="container py-3">
                    <div class="row" id="all_employee_data"></div>
                </div>
            </section>
            </div>
            <div class="modal-footer">
                <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
            </div>
        </div>
    </div>
</div>


<!-- ========UPLOAD REQUIRED DOC========== -->

<div id="UploadRequiredDoc" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload Required Documents</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadRequiredDocfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_id" id="candidate_id">
          <div class="multi-field-wrapper" style="margin-top: 12px; padding: 0 15px;">
            <div class="multi-fields">
              <div class="multi-field">
              <div class="row">
              <div class="col-md-3">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Documents</lable>
                <select id="document_master" name="document_master[]" class="form-control" onchange="get_document_id(this.value);" required>
                  <option value="">--Select--</option>
                  @if(!empty($documentmaster))
                  @foreach($documentmaster as $masterdoc)
                  <option value="{{$masterdoc->id}}">{{ucwords($masterdoc->document_title)}}</option>
                  @endforeach
                  @endif   
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Document Title</lable>
                <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
              </div>
            </div>
            <div class="col-md-3" style="width:40%;flex: 0 0 40%;">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Document</lable>
                <input type="file" name="upload_document[]" class="form-control">
              </div>
            </div>
          </div>
          <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
          </div>
        </div>
          <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
        </div>
            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="required_doc_sub_btn" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ========UPLOAD SIGNED DOC========== -->
<div id="UploadSignedDoc" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload Signed Documents</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadSignedDocfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          
          <input type="hidden" name="candidate_id" id="candidate_signed_doc">
          <div class="col-sm-10">
            <div class="row">
            <div class="col-md-4">
                      <div class="form-group">
                      <lable>Document Type</lable>
                      <input type="text" class="form-control" name="" value="Offer Letter Signed Document" readonly="">
                      <input type="hidden" value="6" name="document_master">
                       <!--  <select id="document_master" name="document_master" class="form-control" onchange="get_signed_doc(this.value);" required>
                          <option value="">--Select--</option>
                          @if(!empty($documentmaster))
                          @foreach($documentmaster as $masterdoc)
                          <option value="{{$masterdoc->id}}">{{ucwords($masterdoc->document_title)}}</option>
                          @endforeach
                          @endif   
                        </select> -->
                      </div>
            </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <lable>Document Title</lable>
                        <input type="text" name="filename" class="form-control" placeholder="Enter Documnet Title" required>
                      </div>
                    </div>
                    <div class="col-md-4" style="width:40%;flex: 0 0 40%;">
                      <div class="form-group">
                        <lable>Select Document</lable>
                        <input type="file" name="upload_document" class="form-control">
                      </div>
                    </div>
            

            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="scanned_doc_sub_btn" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<div id="MedicalTestAppontment" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl" style="max-width: 500px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send medical test appointment to <span id="candidate_name_label_ta"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="MedicalTestAppontmentfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_rec_id" id="candidate_rec_id_ta">
          <div class="col-sm-12">
             <div class="col-md-12">
                <div class="form-group">
                  <lable>Appointment Date and Time</lable>
                  <input type="datetime-local" id="appointment-time" class="form-control" name="appointment_time" min="{{ date('Y-m-d\TH:i') }}" required>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <lable>Place</lable>
                  <textarea id="place" name="place" rows="4" cols="50" class="form-control" required></textarea>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <lable>Comments</lable>
                  <textarea id="comments" name="comments" rows="4" cols="50" class="form-control"></textarea>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <lable>Attachment (optional)</lable>
                  <input type="file" id="attachment" name="attachment" class="form-control">
                </div>
              </div>

            <div class="col-sm-6">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
          </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>


<div id="SendPROEIDProcess" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send to PRO for EID processing of <span id="candidate_name_label_eip"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="SendPROEIDProcessfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_rec_id" id="candidate_rec_id_eip">
          <div class="col-sm-12">
              <div class="col-md-5">
                <div class="form-group">
                  <lable>Select Agency</lable>
                  <select id="agency" onChange="getPro(this.value);" name="agency" class="form-control" style="width:100%" required>
                    <option value="">--Select--</option>
                    <option value="0">Self</option>
                    @if(!empty($vanders))
                    @foreach($vanders as $vander)
                    <option value="{{$vander->id}}">{{ucwords($vander->name)}}</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>

             <div class="col-md-5">
                <div class="form-group">
                  <lable>Select PRO</lable>
                  <select id="manager_name" multiple name="manager_name[]" class="select2 form-control manager_name" style="width:100%" required>
                    @if(!empty($pros))
                    @foreach($pros as $pro)
                    <option value="{{$pro->id}}">{{ucwords($pro->name)}} ({{ $pro->employee_code }})</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <lable>Comments</lable>
                  <textarea id="comments" name="comments" rows="4" cols="50" class="form-control"></textarea>
                </div>
              </div>

            <div class="col-sm-2">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
          </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>

<div id="PROeVisaProcessing" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send to PRO for eVisa processing of <span id="candidate_name_label_ep"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="PROeVisaProcessingfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_rec_id" id="candidate_rec_id_ep">
          <div class="col-sm-12">
              <div class="col-md-5">
                <div class="form-group">
                  <lable>Select Agency</lable>
                  <select id="agency" onChange="getPro(this.value);" name="agency" class="form-control" style="width:100%" required>
                    <option value="">--Select--</option>
                    <option value="0">Self</option>
                    @if(!empty($vanders))
                    @foreach($vanders as $vander)
                    <option value="{{$vander->id}}">{{ucwords($vander->name)}}</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>

              <div class="col-md-5">
                <div class="form-group">
                  <lable>Select PRO</lable>
                  <select id="manager_name" multiple name="manager_name[]" class="select2 form-control manager_name" style="width:100%" required>
                    @if(!empty($pros))
                    @foreach($pros as $pro)
                    <option value="{{$pro->id}}">{{ucwords($pro->name)}} ({{ $pro->employee_code }})</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <lable>Comments</lable>
                  <textarea id="comments" name="comments" rows="4" cols="50" class="form-control"></textarea>
                </div>
              </div>

            <div class="col-sm-2">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
          </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>


<div id="UploadEIDcandidate" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload EID document and send to <span id="candidate_name_label_es"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadEIDcandidatefrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_id" id="candidate_rec_id_es">
          <div class="multi-field-wrapper" style="margin-top: 12px; padding: 0 15px;">
            <div class="multi-fields">
              <div class="multi-field">
              <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Document Title</lable>
                <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Document</lable>
                <input type="file" name="upload_document[]" class="form-control">
              </div>
            </div>
          </div>
          <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
          </div>
        </div>
          <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
        </div>
            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="required_doc_sub_btn2" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>

<div id="UploadMedicalReports" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload medical reports and send to <span id="candidate_name_label_mr"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadMedicalReportsfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_id" id="candidate_rec_id_mr">
          <div class="multi-field-wrapper" style="margin-top: 12px; padding: 0 15px;">
            <div class="multi-fields">
              <div class="multi-field">
              <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Document Title</lable>
                <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Document</lable>
                <input type="file" name="upload_document[]" class="form-control">
              </div>
            </div>
          </div>
          <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
          </div>
        </div>
          <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
        </div>
            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="required_doc_sub_btn2" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>

<div id="UploadeVisaSend" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload eVisa and send to <span id="candidate_name_label_ec"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadeVisaSendfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_id" id="candidate_rec_id_ec">
          <div class="multi-field-wrapper" style="margin-top: 12px; padding: 0 15px;">
            <div class="multi-fields">
              <div class="multi-field">
              <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Document Title</lable>
                <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Document</lable>
                <input type="file" name="upload_document[]" class="form-control">
              </div>
            </div>
          </div>
          <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
          </div>
        </div>
          <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
        </div>
            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="required_doc_sub_btn2" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>

<div id="UploadLCMOLModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload LC/MOL signed copy of <span id="candidate_name_label_sg"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadLCMOLModalfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_id" id="candidate_rec_id_sg">
          <div class="multi-field-wrapper" style="margin-top: 12px; padding: 0 15px;">
            <div class="multi-fields">
              <div class="multi-field">
              <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Document Title</lable>
                <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Document</lable>
                <input type="file" name="upload_document[]" class="form-control">
              </div>
            </div>
          </div>
          <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
          </div>
        </div>
          <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
        </div>
            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="required_doc_sub_btn2" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>


<!-- ========SEND PRO LC========== -->

<div id="SendUploadProLCModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload LC/MOL and send to <span id="candidate_name_label_lc"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="UploadRLCMOLDocfrm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_id" id="candidate_rec_id_lc">
          <div class="multi-field-wrapper" style="margin-top: 12px; padding: 0 15px;">
            <div class="multi-fields">
              <div class="multi-field">
              <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Document Title</lable>
                <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <lable style="margin-bottom: 6px; float: left; width: 100%;">Select Document</lable>
                <input type="file" name="upload_document[]" class="form-control">
              </div>
            </div>
          </div>
          <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
          </div>
        </div>
          <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
        </div>
            <div class="col-sm-4">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" id="required_doc_sub_btn1" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>

<!-- ========SEND PRO LC========== -->

<div id="SendProLCModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Sending to PRO for LC/MOL process of <span id="candidate_name_label_pro"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="imageUploadFormPROLC" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_rec_id" id="candidate_rec_id_pro">
          <div class="col-sm-12">
            <div class="col-md-5">
                <div class="form-group">
                  <lable>Select Agency</lable>
                  <select id="agency" onChange="getPro(this.value);" name="agency" class="form-control" style="width:100%" required>
                    <option value="">--Select--</option>
                    <option value="0">Self</option>
                    @if(!empty($vanders))
                    @foreach($vanders as $vander)
                    <option value="{{$vander->id}}">{{ucwords($vander->name)}}</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>
             <div class="col-md-5">
                <div class="form-group">
                  <lable>Select PRO</lable>
                  <select multiple id="manager_name_2" data-live-search="true" name="manager_name[]" class="select2 manager_name form-control" style="width:100%" required>
                   
                    @if(!empty($pros))
                    @foreach($pros as $pro)
                    <option value="{{$pro->id}}">{{ucwords($pro->name)}} ({{ $pro->employee_code }})</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <lable>Comments</lable>
                  <textarea id="comments" name="comments" rows="4" cols="50" class="form-control"></textarea>
                </div>
              </div>

            <div class="col-sm-2">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
          </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>


<!-- ========SEND VISA APPROVAL========== -->
<div id="SendVisaApprovalModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Sending eVisa Approval for candidate <span id="candidate_name_label"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
        <form id="imageUploadForm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="candidate_rec_id" id="candidate_rec_id">
          <div class="col-sm-12">
             <div class="col-md-5">
                <div class="form-group">
                  <lable>Select Manager</lable>
                  <select id="manager_name" name="manager_name" class="form-control" style="width:100%" required>
                    <option value="">--Select--</option>
                    @if(!empty($managers))
                    @foreach($managers as $managers_rec)
                    <option value="{{$managers_rec->id}}">{{ucwords($managers_rec->name)}} ({{ $managers_rec->employee_code }})</option>
                    @endforeach
                    @endif   
                  </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <lable>Comments</lable>
                  <textarea id="comments" name="comments" rows="4" cols="50" class="form-control"></textarea>
                </div>
              </div>

            <div class="col-sm-2">
              <div class="form-group" style="margin-top: 32px;"> 
                <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
              </div>
            </div>
          </div>
        </form>
        <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
      </div>
    </div>
  </div>
</div>
<!-- ==========END POPUP BOX============= -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
  function upload_required_doc(id){ 
       $('#document_master').prop('selectedIndex',0);
       var candidates = $('#candidate_id').val(id);
  }
  function upload_signed_doc(id){    
       var candidate_signed_doc_id = $('#candidate_signed_doc').val(id);
  }

  function Upload_EID_candidate(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_es').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_es').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_es').val(xhr.data.id);
                  } 
              }
          });
        }  
  }

  function Send_PRO_EID_process(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_eip').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_eip').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_eip').val(xhr.data.id);
                  } 
              }
          });
        }  
  }

  function Upload_medical_reports(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_mr').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_mr').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_mr').val(xhr.data.id);
                  } 
              }
          });
        }  
  }

  function send_medical_test_appontment(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_ta').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_ta').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_ta').val(xhr.data.id);
                  } 
              }
          });
        }  
  }

  function upload_eVisa_send_to_candidate(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_ec').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_ec').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_ec').val(xhr.data.id);
                  } 
              }
          });
        }  
  }
  
  function send_PRO_eVisa_processing(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_ep').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_ep').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_ep').val(xhr.data.id);
                  } 
              }
          });
        }  
  }
  
  function upload_LC_MOL_signed(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_sg').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_sg').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_sg').val(xhr.data.id);
                  } 
                   
              }
          });
        }  
  }
  
  function upload_LC_MOL(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_lc').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_lc').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_lc').val(xhr.data.id);
                  } 
                   
              }
          });
        }  
  }

  function send_to_pro(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label_pro').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name_pro').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id_pro').val(xhr.data.id);
                  } 
                   
              }
          });
        }  
  }

  function send_visa_approval(id){  
       var candidates_evisa = $('#candidate_id').val(id);
        if(id!=""){  
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-candidate-name')}}",
              data: {
                  candidate_id:id
              },
              success: function(xhr) { 
                  if(xhr.status==200){
                  $('#candidate_name_label').html(xhr.data.candidate_name);
                  var candidate_names = $('#candidate_name').val(xhr.data.candidate_name);
                  var candidate_id_datas = $('#candidate_rec_id').val(xhr.data.id);
                  } 
                   
              }
          });
        }  
  }
</script>
<script>
  var loadFile = function(event) {
      document.getElementById('output').setAttribute("style",
          "width: 8rem;height: 8rem;border-radius: 0.25rem;object-fit: contain;max-height: 51px;max-width: 10rem;margin-top: -9px;"
          );
      var reader = new FileReader();
      reader.onload = function() {
          var output = document.getElementById('output');
          output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
  };
</script>

<script>
    function get_document_id(document_id) { 
        var candidate_id=$('#candidate_id').val();
        if(candidate_id!=""){
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-documents')}}",
              data: {
                  document_id: document_id,
                  candidate_id:candidate_id
              },
              success: function(xhr) {
                  //var datas = xhr.data;
                  if(xhr.status==400){
                      alert(xhr.data);
                      $('#required_doc_sub_btn'). prop('disabled', true);
                  }else{
                    $('#required_doc_sub_btn'). prop('disabled', false);
                  } 
                   
              }
          });
        }  
    }
</script>


<script>
$(document).ready(function (e) {
      $('#UploadEIDcandidate').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadEIDcandidatefrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-eid-send-candidate')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("Document uploaded successfully");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadRequiredDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert(data.msg);
                      $('#UploadRequiredDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadRequiredDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });


$(document).ready(function (e) {
      $('#UploadMedicalReports').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadMedicalReportsfrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-medical-reports-send-candidate')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("Document uploaded successfully");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadRequiredDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert(data.msg);
                      $('#UploadRequiredDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadRequiredDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });


$(document).ready(function (e) {
      $('#UploadeVisaSend').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadeVisaSendfrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-evisa-send-candidate')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("Document uploaded successfully");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadRequiredDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert(data.msg);
                      $('#UploadRequiredDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadRequiredDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });


$(document).ready(function (e) {
      $('#UploadLCMOLModal').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadLCMOLModalfrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-lc-mol-signed-copy')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("Document uploaded successfully");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadRequiredDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert(data.msg);
                      $('#UploadRequiredDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadRequiredDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });


$(document).ready(function (e) {
      $('#SendUploadProLCModal').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadRLCMOLDocfrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-lc-mol')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("LC/Mol uploaded and sent to the candidate for Thumb Impression on the same");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadRequiredDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert(data.msg);
                      $('#UploadRequiredDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadRequiredDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });


  $(document).ready(function (e) {
      $('#UploadRequiredDoc').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadRequiredDocfrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-required-doc')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("Document uploaded successfully");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadRequiredDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  }else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert("This document already uploaded.");
                      $('#UploadRequiredDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadRequiredDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>

<!-- ------------SIGNED DOC UPLOADINGS------------------- -->
<script>
    function get_signed_doc(document_id) { 
        var candidate_id=$('#candidate_signed_doc').val();
        if(candidate_id!=""){
          $.ajax({
              type: "POST",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
              },
              url: "{{url('ajax/get-scanned-documents')}}",
              data: {
                  document_id: document_id,
                  candidate_id:candidate_id
              },
              success: function(xhr) {
                  var datas = xhr.data;
                  if(xhr.status==400){
                      alert(xhr.data);
                      $('#scanned_doc_sub_btn'). prop('disabled', true);
                  }else{
                    $('#scanned_doc_sub_btn'). prop('disabled', false);
                  } 
                   
              }
          });
        }  
    }
</script>
<script>
  $(document).ready(function (e) {
      $('#UploadSignedDoc').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(document.getElementById("UploadSignedDocfrm"));
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-signed-doc')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){  
                  if(data.status==200){
                      spinner.hide();
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("Signed document uploaded successfully");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#UploadSignedDoc').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  }else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert("This document already uploaded.");
                      $('#UploadSignedDoc').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#UploadSignedDoc').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>


<script>
  
  $(document).ready(function (e) {
      $('#MedicalTestAppontmentfrm').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(this);

          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/send-medical-test-appointment')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){ //alert(data);
                  if(data.status==200){
                      spinner.hide();
                      //alert(JSON.stringify(data));
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("An email has been sent to the candidate for medical test appointment.");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#myModal').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert("Medical test appointment already sent.");
                      $('#myModal').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#myModal').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>

<script>
  $(document).ready(function (e) {
      $('#SendPROEIDProcessfrm').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(this);
         //alert(JSON.stringify(formData));
        //  retrun false;
          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/send-pro-request-for-eid-process')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){ //alert(data);
                  if(data.status==200){
                      spinner.hide();
                      //alert(JSON.stringify(data));
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("An email has been sent to the PRO for EID process.");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#myModal').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert(data.msg);
                      $('#myModal').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#myModal').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>
<script>
  
  $(document).ready(function (e) {
      $('#PROeVisaProcessingfrm').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(this);
         //alert(JSON.stringify(formData));
        //  retrun false;

          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/send-pro-request-for-evisa-process')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){ //alert(data);
                  if(data.status==200){
                      spinner.hide();
                      //alert(JSON.stringify(data));
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("An email has been sent to the PRO for eVisa processing.");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#myModal').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert("Record Already Send for eVisa Process.");
                      $('#myModal').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#myModal').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>

<script>
  $(document).ready(function (e) {
      $('#imageUploadFormPROLC').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(this);
         //alert(JSON.stringify(formData));
        //  retrun false;

          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/send-pro-request-for-lc-mol')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){ //alert(data);
                  if(data.status==200){
                      spinner.hide();
                      //alert(JSON.stringify(data));
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("An email has been sent to the PRO for LC/MOL Process.");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#myModal').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  } else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert("Record Already Send for LC/MOL Process.");
                      $('#myModal').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#myModal').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>

<script>
  $(document).ready(function (e) {
      $('#imageUploadForm').on('submit',(function(e) {  
          e.preventDefault();
          var formData = new FormData(this);
         //alert(JSON.stringify(formData));
        //  retrun false;

          $('#alert-image').fadeIn();
          var spinner = $('#loader');
          spinner.show();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
              type:'POST',
              url: "{{url('ajax/upload-document-for-visa-approval')}}",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success:function(data){ //alert(data);
                  if(data.status==200){
                      spinner.hide();
                      //alert(JSON.stringify(data));
                      $('#imageUploadForm')[0].reset();
                      $('#msg').show();
                      $('#msg').html(data).fadeIn('slow');
                      $('#msg').delay(5000).fadeOut('slow');
                      alert("An email has been sent to the manager for e-visa approval.");
                      $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                      $('#myModal').modal('hide');
                      $('#imageUploadForm')[0].reset();
                       spinner.hide();
                      location.reload(); 
  
                  }else{
                      $('#imageUploadForm')[0].reset();
                      spinner.hide();
                      $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                      alert("Record Already Send for visa approval.");
                      $('#myModal').modal('hide');
                      spinner.hide();
                  }
                  setTimeout(function () {
                      $('#imageUploadForm')[0].reset();
                      $('#alert-image').fadeOut();
                      $('#myModal').modal('hide');
                       spinner.hide();
                  }, 2000);
                  spinner.hide();
              }
          });
      }));
  });
</script>
<script>
  $('.multi-field-wrapper').each(function() {
      var $wrapper = $('.multi-fields', this);
      $(".add-field", $(this)).click(function(e) {
          $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
      });
      $('.multi-field .remove-field', $wrapper).click(function() {
          if ($('.multi-field', $wrapper).length > 1)
              $(this).parent('.multi-field').remove();
      });
  });
</script>
<!-- <script>
    function show_data(id) {
        $.ajax({
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-candidate-full-profile')}}/"+id,
            data: {
                id: id
            },
            success:function(xhr){
                    if(xhr.status==200){ 
                      var datas = xhr.data;
                     for (var i = 0; i < datas.length; i++) {

                        if(datas[i].hiring_status=='1')
                        {
                            var hiringstatus='Offer Letter Send';
                        }

                        if(datas[i].hiring_status=='4')
                        {
                            var hiringstatus='Process For eVisa Approval';
                        }
                        if(datas[i].hiring_status=='7')
                        {
                            var hiringstatus='Request Send for eVisa';
                        }

                        var html='<tr>'+
                        '<td>'+datas[i].candidate_name+'</td>'+
                        '<td>'+datas[i].position_name+'</td>'+
                        '<td>'+datas[i].manager_name+'</td>'+                
                        '<td>'+datas[i].candidate_gender+'</td>'+
                        '<td>'+datas[i].candidate_email+'</td>'+
                        '<td>'+datas[i].candidate_mobile+'</td>'+
                        '<td>'+datas[i].candidate_salary+'</td>'+
                        '<td>'+datas[i].hr_email+'</td>'+
                        '<td>'+datas[i].created_at+'</td>'+
                        '<td>'+hiringstatus+'</td>'+
                        '</td></tr>';
                        $('#candidate_data').html(html);
                      }  
                    }
                }
        });
    }
</script> -->

<script>
  $(document).ready(function () {
      var datatable = $('#examples').dataTable({
      dom: 'Bfrtip',
      buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
      ],
      });
  });
   
</script>

<!-- --------GET COMPLETE DETAILS ----------- -->
<script>
    function get_candidate_data(id){  
       var spinner = $('#loader');
       spinner.show();
        $.get("{{url('ajax/get-candidate-all-hiring-details')}}/"+id+"",function(xhr){ 
          //alert(xhr);
            $('#all_employee_data').html(xhr);
            spinner.hide();
        });
    }

function getPro(val) {
  $.ajax({
    type: "GET",
    url: "{{ route('getPro') }}",
    data: {'company_id' : val},
    success: function(data){
        $(".manager_name").html(data);
    }
  });
}
  </script>   

<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script type="text/javascript">
     $(document).ready(function() {
$(".select2").select2();
});
</script>
<!-- --------END GET COMPLETE DETAILS ----------- -->
@endsection('content')