@extends('layouts.organization.app')
@section('content')
<style>
    .emp_head_h{
        height: 59px !important;
    }
    hr{
        margin: 7px -46px 19px;
        border: 0;
        border-top: 1px solid #c9c7c7;
    }
    @media only screen and (max-width: 768px) {
        #output{
            width: 4rem !important;
            height: 8rem !important;
            border-radius: 0.25rem !important;
            object-fit: contain !important;
            max-height: 47px !important;
            max-width: 9rem !important;
            margin-top: -32px !important;
            float: right !important;
        }
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <form class="forms-sample" action="{{url('add-employees')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card" id="employee_details">
                        <div class="card-header emp_head_h">
                            <div class="row">
                                <div class="col-md-10">
                                <h5 class="">Employee Information</h5>
                                </div>
                                <div class="col-md-2">
                                    <img id="output" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Employee Code</label>
                                        <input type="text" class="form-control" id="emp_code" name="emp_code"
                                            placeholder="Employee Code" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Second Name</label>
                                        <input type="text" class="form-control" id="second_name" name="second_name"
                                            placeholder="Second Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control form-control-sm" id="department_id" name="department_id">
                                            <option value="">Select Designation Name</option>
                                                <option value="it">IT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <select class="form-control form-control-sm" id="designation_id" name="designation_id">
                                            @if(!empty($designation_name))
                                                <option value="">Select Designation Name</option>
                                                @foreach($designation_name as $row)
                                                    <option value="{{$row->id}}">{{$row->position_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>DOB</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="dob"
                                            placeholder="dob" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>UAE Mobile No.</label>
                                        <input type="text" class="form-control" id="uae_mob_no" name="uae_mob_no"
                                            placeholder="UAE Mobile No." required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Home Country Contact No.</label>
                                        <input type="text" class="form-control" id="uae_mob_no" name="uae_mob_no"
                                            placeholder="UAE Mobile No." required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Emergency Contact Person / No.</label>
                                        <input type="text" class="form-control" id="uae_mob_no" name="uae_mob_no"
                                            placeholder="UAE Mobile No." required>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Marital Status</label>
                                        <select class="form-control" id="marital_status" name="marital_status" required>
                                            <option value="">Select Marital Status</option>
                                            <option value="Single">Single</option>
                                            <option value="married">Married</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Father Name</label>
                                        <input type="text" class="form-control" id="father_name" name="father_name"
                                            placeholder="Father Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Mother Name</label>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name"
                                            placeholder="Mother Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Nominee</label>
                                        <input type="text" class="form-control" id="nominee" name="nominee"
                                            placeholder="Nominee">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Email" onkeyup="CheckEmail(this.value);" required>
                                        <div class="help-block checking_email" style="font-size: 12px;"></div>
                                    </div>
                                </div>
                                @if(empty($update->user_name))
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="text" class="form-control" id="password" name="password"
                                            placeholder="Password" required>
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality"
                                            placeholder="Nationality">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion"
                                            placeholder="Religion">
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Source Name</label>
                                        <select id="source_id" name="source_id" class="form-control">
                                            @if(!empty($source_name))
                                                <option value="">Select Source</option>
                                                @foreach($source_name as $row)
                                                    <option value="{{$row->id}}">{{$row->source_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Notice Period</label>
                                        <select id="notice_id" name="notice_id" class="form-control">
                                            @if(!empty($notice_period))
                                                <option value="">Select Period</option>
                                                @foreach($notice_period as $row)
                                                    <option value="{{$row->id}}">{{$row->notice_days}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Profile upload</label>
                                        <input type="file" name="profile" id="profile_pic" class="form-control"
                                            onchange="loadFile(event)">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <span id="next_button"
                                        class="btn btn-primary btn-sm mr-2 employee_button">Next</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="employee_contact" style="display:none">
                        <div class="card-header">
                            <h5 class="">Emergency Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Name">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Relation</label>
                                            <input type="text" class="form-control" id="relation" name="relation" placeholder="Relation">
                                        </div>
                                    </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                            placeholder="Mobile" pattern="[0-9]*" maxlength="10" minlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            placeholder="Address">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select id="state_id" name="state_id" onchange="get_state_id(this.value);"
                                            class="form-control">
                                            @if(!empty($state))
                                            <option value="">Select State</option>
                                            @foreach($state as $st)
                                            <option value="{{$st->stateID}}">{{$st->stateName}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>City</label>
                                        <select id="city_id" name="city_id" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control" id="pincode" name="pincode"
                                            placeholder="Pincode" pattern="[0-9]*" maxlength="6" minlength="6"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <span id="back_button_contact" class="btn btn-primary btn-sm mr-2">Back</span>
                                    <span id="next_button_contact"
                                        class="btn btn-primary btn-sm mr-2 employee_button">Next</span>
                                </div>
                            </div>
                        </div>
                    </div>
                

                    <div class="card" id="employee_education" style="display:none">
                        <div class="card-header">
                            <h5 class="">Education Qualification</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="multi-field-wrapper">
                                    <div class="multi-fields">
                                        <div class="multi-field">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Education Name</label>
                                                        <select class="form-control" id="education_type"
                                                            name="education_type[]">
                                                            @if(!empty($education_name))
                                                                <option value="">Select Education Name</option>
                                                                @foreach($education_name as $row)
                                                                    <option value="{{$row->id}}">{{$row->education_title}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Course Name</label>
                                                        <input type="text" class="form-control" id="course_name"
                                                            name="course_name[]" placeholder="Course Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Board / Institution / University</label>
                                                        <input type="text" class="form-control" id="board_university"
                                                            name="board_university[]" placeholder="Board University">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Percentage / CGPA</label>
                                                        <input type="text" class="form-control" id="percentage_cgpa"
                                                            name="percentage_cgpa[]" placeholder="Percentage CGPA">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>From Year</label>
                                                        <input type="date" class="form-control" id="from_year"
                                                            name="from_year[]" placeholder="From Year">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>To Year</label>
                                                        <input type="date" class="form-control" id="to_year" name="to_year[]"
                                                            placeholder="To Year">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Country Studied In</label>
                                                        <input type="text" class="form-control" id="country_studied" name="country_studied[]"
                                                            placeholder="Country Studied In">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Document Upload</label>
                                                        <input type="file" name="document[]" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="remove-field btn-danger btn-sm float-right remove-btn"
                                                style="width:3%;margin-top: 0rem;margin-right: 7rem;font-size: 18px;padding: 0px;margin-left: 29px;display: inherit;"><i
                                                    class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <span class="add-field remove-field btn-success btn-sm float-right add-btn"
                                        style="margin-top: 0rem;margin-right: -155px;padding: 5px;"><i
                                            class="fa fa-plus"></i> Add More</span><br>
                                </div>
                                <div class="col-sm-12">
                                    <span id="back_button_education" class="btn btn-primary btn-sm mr-2">Back</span>
                                    <span id="next_button_education"
                                        class="btn btn-primary btn-sm mr-2 employee_button">Next</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="employee_bank" style="display:none">
                        <div class="card-header">
                            <h5 class="">Bank Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Account Holder's Name</label>
                                        <input type="text" class="form-control" id="acc_holder_name" name="acc_holder_name"
                                            placeholder="Account Holder Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <select class="form-control form-control-sm" id="bank_id" name="bank_id">
                                            @if(!empty($bank_name))
                                                <option value="">Select Education Name</option>
                                                @foreach($bank_name as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input type="text" class="form-control" id="acc_number" name="acc_number"
                                            placeholder="Account Number">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>IBAN Number</label>
                                        <input type="text" class="form-control" id="iban_no" name="iban_no"
                                            placeholder="IBAN Number">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Medical Insurance Plan</label>
                                        <select class="form-control form-control-sm" id="medi_insur_plan" name="medi_insur_plan">
                                            <option value="">Select Education Name</option>
                                            <option value="Basic Plan">Basic Plan</option>
                                            <option value="Enhanced Plan">Enhanced Plan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Branch Name</label>
                                        <input type="text" class="form-control" id="branch_name" name="branch_name"
                                            placeholder="Branch Name">
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <span id="back_button_bank" class="btn btn-primary btn-sm mr-2">Back</span>
                                    <span id="next_button_bank"
                                        class="btn btn-primary btn-sm mr-2 employee_button">Next</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="employee_company" style="display:none">
                        <div class="card-header">
                            <h5 class="">Previous Company Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="multi-field-company">
                                    <div class="multi-fields-company">
                                        <div class="multi-field-company">
                                            <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Company Name</label>
                                                    <input type="text" class="form-control" id="comp_name" name="comp_name[]" placeholder="Company Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Designation</label>
                                                    <input type="text" class="form-control" id="designation" name="designation[]" placeholder="Designation">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Date Of Joining</label>
                                                    <input type="date" class="form-control" id="date_of_joining" name="date_of_joining[]" placeholder="Date Of Joining">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Date Of Resignation</label>
                                                    <input type="date" class="form-control" id="date_of_resignation" name="date_of_resignation[]" placeholder="Date Of Resignation">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>CTC</label>
                                                    <input type="text" class="form-control" id="ctc" name="ctc[]" placeholder="CTC">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Reason For Leaving Company</label>
                                                    <input type="text" class="form-control" id="reason_for_leav_comp" name="reason_for_leav_comp[]" placeholder="Reason For Leaving Company">
                                                </div>
                                            </div>
                                            </div>
                                            <button type="button" class="remove-field-company btn-danger btn-sm float-right remove-btn" style="width:3%;margin-top: -60px;margin-right: 494px;font-size: 20px;padding: 0px;"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <!-- <span class="add-field remove-field btn-success btn-sm float-right add-btn" style="margin-top: -60px;margin-right: 118px;padding: 5px;"><i class="fa fa-plus"></i> Add More</span><br> -->
                                    <button type="button" class="add-field-company remove-field-company btn-success btn-sm float-right add-btn" style="margin-top: -62px;margin-right: 363px;padding: 5px;"><i class="fa fa-plus"></i> Add More</button>
                                </div>
                                <div class="col-sm-12">
                                <span id="back_button_company" class="btn btn-primary btn-sm mr-2">Back</span>
                                    <span id="next_button_company" class="btn btn-primary btn-sm mr-2 employee_button">Next</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="employee_document" style="display:none">
                        <div class="card-header">
                            <h5 class="">Other Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="multi-field-document">
                                    <div class="multi-fields-document">
                                        <div class="multi-field-document">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Passport Number</label>
                                                        <input type="text" class="form-control" id="passport_no" name="passport_no" placeholder="Passport Number">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Country</label>
                                                        <select name="country_name" id="country_name" class="form-control">
                                                            <option value="">Select Country</option>
                                                            <option value="india">India</option>
                                                            <option value="uae">UAE</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Issue</label>
                                                        <input type="date" name="pn_date_of_issue" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Expiry</label>
                                                        <input type="date" name="pn_date_of_expiry" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Upload Passport</label>
                                                        <input type="file" name="upload_passport" class="form-control">
                                                    </div>
                                                </div>
                                            </div><hr>



                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Driving License Number</label>
                                                        <input type="text" name="driving_license_no" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Issue</label>
                                                        <input type="date" name="dl_date_of_issue" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Expiry</label>
                                                        <input type="date" name="dl_date_of_expiry" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Upload Driving License</label>
                                                        <input type="file" name="upload_driv_lice" class="form-control">
                                                    </div>
                                                </div>
                                            </div><hr>


                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Emirates Id Number</label>
                                                        <input type="text" name="emirates_id_no" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Issue</label>
                                                        <input type="date" name="emn_date_of_issue" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Expiry</label>
                                                        <input type="date" name="emn_date_of_expiry" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Upload Emirates Id</label>
                                                        <input type="file" name="upload_emirate_id" class="form-control">
                                                    </div>
                                                </div>
                                            </div><hr>


                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Pan  Card</label>
                                                        <input type="text" name="pan_card" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Upload Pan  Card</label>
                                                        <input type="file" name="upload_pan_card" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Voter ID Card</label>
                                                        <input type="text" name="voter_id_card" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Voter ID Card</label>
                                                        <input type="file" name="upload_voter_id_card" class="form-control">
                                                    </div>
                                                </div>
                                            </div><hr>

                                            
                                            <!-- <button type="button" class="remove-field-document btn-danger btn-sm float-right remove-btn" style="width:3%;margin-top: -60px;margin-right: 298px;font-size: 20px;padding: 0px;"><i class="fa fa-trash"></i></button> -->
                                        </div>
                                    </div>
                                    <!-- <button type="button" class="add-field-document remove-field-document btn-success btn-sm float-right add-btn" style="margin-top: -61px;margin-right: 174px;padding: 5px;"><i class="fa fa-plus"></i> Add More</button><br> -->
                                    <div class="col-sm-3">
                                        <span id="back_button_document" class="btn btn-primary btn-sm mr-2">Back</span>
                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
    function CheckEmail(email) {
        $('.organisation_button').removeAttr("disabled");
        var check_container = $('.checking_email');
        var check_input = email;
        if (check_input == '') {
            check_container.empty();
            return false;
        }
        check_container.removeClass('text-danger').removeClass('text-primary').html(
            '<span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
        if (check_input.length >= 4) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/check-email')}}",
                data: "email=" + check_input,
                success: function(data) {
                    if (data.status == 200) {
                        check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass(
                            'text-danger').addClass('text-primary');
                        $('.employee_button').removeAttr("disabled");
                    } else if (data.status == 404) {
                        check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass(
                            'text-primary').addClass('text-danger');
                        $('.employee_button').attr("disabled", true);
                    } else if (data.status == 401) {
                        check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass(
                            'text-primary').addClass('text-danger');
                        $('.employee_button').attr("disabled", true);
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
    $("#next_button").on('click', function() {
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var gender = $('#gender').val();
        var date_of_birth = $('#date_of_birth').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var salary = $('#salary').val();
        var profile_pic = $('#profile_pic').val();
        var source_id = $('#source_id').val();
        var notice_id = $('#notice_id').val();
        var designation_id = $('#designation_id').val();
        if (first_name === '') {
            toastr.error("Please Enter First Name");
        } else if (last_name === '') {
            toastr.error("Please Enter Last Name");
        } else if (gender === '') {
            toastr.error("Please Enter Gender");
        } else if (date_of_birth === '') {
            toastr.error("Please Enter Date Of Birth");
        } else if (email === '') {
            toastr.error("Please Enter Email");
        } else if (password === '') {
            toastr.error("Please Enter Password");
        } else if (salary === '') {
            toastr.error("Please Enter Salary");
        } else if (profile_pic === '') {
            toastr.error("Please Enter Profile Pic");
        } else if (source_id === '') {
            toastr.error("Please Select Source Name");
        } else if (notice_id === '') {
            toastr.error("Please Select Notice");
        } else if (designation_id === '') {
            toastr.error("Please Select Designation");
        } else {
            $('#employee_details').hide();
            $('#employee_contact').show();
        }
    });
    $("#next_button_contact").on('click', function() {
        var mobile = $('#mobile').val();
        var state_id = $('#state_id').val();
        var city_id = $('#city_id').val();
        var address = $('#address').val();
        var pincode = $('#pincode').val();

        if (mobile === '') {
            toastr.error("Please Enter Mobile No");
        } else if (state_id === '') {
            toastr.error("Please Enter State");
        } else if (city_id === '') {
            toastr.error("Please Enter City");
        } else if (address === '') {
            toastr.error("Please Enter Address");
        } else if (pincode === '') {
            toastr.error("Please Enter Pincode");
        } else {
            $('#employee_details').hide();
            $('#employee_contact').hide();
            $('#employee_education').show();
        }
    });
    $("#back_button_contact").on('click', function() {
        $('#employee_details').show();
        $('#employee_contact').hide();
    });
    $("#back_button_education").on('click', function() {
        $('#employee_contact').show();
        $('#employee_education').hide();
    });
    $("#next_button_education").on('click', function() {
        $('#employee_education').hide();
        $('#employee_bank').show();
    });
    $("#next_button_bank").on('click', function() {
        $('#employee_bank').hide();
        $('#employee_company').show();
    });
    $("#back_button_bank").on('click', function() {
        $('#employee_education').show();
        $('#employee_bank').hide();
    });
    $("#back_button_company").on('click', function() {
        $('#employee_bank').show();
        $('#employee_company').hide();
    });
    $("#next_button_company").on('click', function() {
        $('#employee_company').hide();
        $('#employee_document').show();
    });
    $("#back_button_document").on('click', function() {
        $('#employee_company').show();
        $('#employee_document').hide();
    });

    function get_state_id(id) {
        $('#city_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-city')}}",
            data: {
                state_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                for (var i = 0; i < datas.length; i++) {
                    $('#city_id').append('<option value="' + datas[i].cityID + '">' + datas[i].cityName +
                        '</option>');
                }
            }
        });
    }
    </script>
    <script>
    $('.multi-field-wrapper').each(function() {
        var $wrapper = $('.multi-fields', this);
        $(".add-field", $(this)).click(function(e) {
            $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('')
                .focus();
        });
        $('.multi-field .remove-field', $wrapper).click(function() {
            if ($('.multi-field', $wrapper).length > 1)
                $(this).parent('.multi-field').remove();
        });
    });
    </script>
    <script>
        $('.multi-field-company').each(function() {
            var $wrapper = $('.multi-fields-company', this);
            $(".add-field-company", $(this)).click(function(e) {
                $('.multi-field-company:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
            });
            $('.multi-field-company .remove-field-company', $wrapper).click(function() {
                if ($('.multi-field-company', $wrapper).length > 1)
                    $(this).parent('.multi-field-company').remove();
            });
        });
    </script>
    <script>
        $('.multi-field-document').each(function() {
            var $wrapper = $('.multi-fields-document', this);
            $(".add-field-document", $(this)).click(function(e) {
                $('.multi-field-document:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
            });
            $('.multi-field-document .remove-field-document', $wrapper).click(function() {
                if ($('.multi-field-document', $wrapper).length > 1)
                    $(this).parent('.multi-field-document').remove();
            });
        });
    </script>
    @endsection('content')