@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Employee Reporting</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('employee-reporting')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select Possion</label>
                                    <select class="form-control" id="position_id" onchange="get_reporting(this.value)" name="position_id" required>
                                        <option value="">Select Possion</option>
                                        @if(!empty($position_master))
                                            @foreach($position_master as $row)
                                                <option value="{{$row->id}}"
                                                @if(!empty($update->position_id))
                                                    @if($update->position_id==$row->id)
                                                        selected
                                                    @endif
                                                @endif
                                                >{{$row->position_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select Reporting Employee</label>
                                    <select class="form-control" id="reporting_id" name="reporting_id" required>
                                        @if(!empty($reporting_id))
                                            @if(!empty($update_emp))
                                                @foreach($update_emp as $up_emp)
                                                    <option value="{{$up_emp->user_id}}">{{$up_emp->first_name}} {{$up_emp->last_name}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select Employees</label>
                                    <select class="form-control select2min" id="employee_id" name="employee_id[]" multiple required>
                                        @if(!empty($emp_detail))
                                            @foreach($emp_detail as $row)
                                                <option value="{{$row->user_id}}"
                                                @if(!empty($update->employee_id))
                                                    <?php $a = json_decode($update->employee_id);
                                                    if(in_array($row->user_id, $a)){
                                                        echo 'selected';
                                                    }?>
                                                @endif
                                                >{{$row->first_name}} {{$row->last_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>emp_reportings
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Reporting List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reporting</th>
                                    <th>Employee Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <body>
                                @if(!empty($emp_reportings))
                                    @foreach($emp_reportings as $rows)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->org_name}} & {{$rows->report_name}}</td>
                                            <td>
                                                <?php if(!empty($rows->employee_id)){
                                                    $employee_id = json_decode($rows->employee_id);
                                                    $count = count($employee_id);
                                                    for($i=0;$i < $count;$i++){
                                                        $var = App\Models\EmpDetail::select('first_name')->where('user_id',$employee_id[$i])->first();
                                                        echo '<a onclick="get_users_data('.$employee_id[$i].')" data-toggle="modal" data-target="#myModal"  href="#" class="btn btn-info btn-xs mx-1 mb-1" style="padding: 0.23rem 0.3rem;">'.$var->first_name.'</a>';
                                                    }
                                                }?>
                                            </td>
                                            <td>
                                                <a href="{{url('employee-reporting',$rows->id)}}" class="text-primary mx-1"><i class="fa fa-edit"></i></a>
                                                <a href="{{url('add-organization',$rows->id)}}" class="text-danger"><i class="fa fa-trash"></i></a>
                                            </td>
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
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title d-flex"><div id="user_profile"></div>&nbsp;&nbsp; <span id="full_name"></span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body" id="reason_for_leav_comp_desc">
            <section style="background-color: #eee;">
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4 mb-md-0">
                                <div class="card-body">
                                    <h5 class="mb-2"> Personal Deatils </h5>
                                    <div class="row" id="personal_deatils"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <h5 class="mb-2"> Contact Details </h5>
                                <div class="row" id="contacts_deatils"></div>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <h5 class="mb-2"> Bank Details </h5>
                                <div class="row" id="bank_deatils"></div>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <h5 class="mb-2"> Document Details </h5>
                                <div class="row">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <td><b>Document Title</b></td>
                                                <td><b>Doucment File</b></td>
                                            </tr>
                                        </thead>
                                        <tbody id="document_deatils">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card mb-4 mb-md-0">
                                <div class="card-body">
                                    <h5 class="mb-2"> Education Deatils </h5>
                                    <div class="row">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <td><b>Education Title</b></td>
                                                    <td><b>Course Name</b></td>
                                                    <td><b>Board University</b></td>
                                                    <td><b>From Year</b></td>
                                                    <td><b>To Year</b></td>
                                                    <td><b>Percentage CGPA</b></td>
                                                    <td><b>Document</b></td>
                                                </tr>
                                            </thead>
                                            <tbody id="education_deatils">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card mb-4 mb-md-0">
                                <div class="card-body">
                                    <h5 class="mb-2"> Companies Deatils </h5>
                                    <div class="row">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <td><b>Companie Name</b></td>
                                                    <td><b>Designation</b></td>
                                                    <td><b>Date Of Joining</b></td>
                                                    <td><b>Date Of Resignation</b></td>
                                                    <td><b>CTC</b></td>
                                                    <td><b>Reason For Leaving</b></td>
                                                </tr>
                                            </thead>
                                            <tbody id="companie_deatils">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            </div>
            <div class="modal-footer">
                <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
            </div>
        </div>
    </div>
</div>
    <script>
    function get_reporting(id) {
        $('#reporting_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-reporting')}}",
            data: {
                reporting:id
            },
            success: function(xhr) {
                var datas = xhr.data;
                if(datas.length!=''){
                    for (var i = 0; i < datas.length; i++) {
                        $('#reporting_id').append('<option value="'+datas[i].user_id+'">'+datas[i].first_name+' '+datas[i].last_name+'</option>');
                    }
                }else{
                    $('#reporting_id').empty();
                }
            }
        });
    }
    function get_users_data(id){
        var spinner = $('#loader');
        spinner.show();
        $.get("{{url('ajax/get-employee-all-details')}}/"+id+"",function(xhr){
            if(xhr.status==200){
                var personal_data = '<table class="table table-condensed">'+
                '<tbody>'+
                    '<tr>'+
                        '<td><b>Gender</b></td>'+
                        '<td>'+xhr.personal.gender+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>DOB</b></td>'+
                        '<td>'+dateFormate(xhr.personal.dob)+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Father Name</b></td>'+
                        '<td>'+xhr.personal.father_name+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Mother Name</b></td>'+
                        '<td>'+xhr.personal.mother_name+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Salary</b></td>'+
                        '<td>'+parseFloat(xhr.personal.salary)+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Designation</b></td>'+
                        '<td>'+xhr.personal.position_name+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Source From</b></td>'+
                        '<td>'+xhr.personal.source_name+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Notice Period</b></td>'+
                        '<td>'+xhr.personal.notice_days+'</td>'+
                    '</tr>'+
                '</tbody>'+
                '</table>';
                $('#full_name').text(xhr.personal.name);
                $('#user_profile').html('<img src="{{asset("employee/profile")}}/'+xhr.personal.profile+'" class="img-fluid" style="width: 31px;height: 31px;"/>');
                $('#personal_deatils').html(personal_data);


                var contacts = '<table class="table table-condensed">'+
                '<tbody>'+
                    '<tr>'+
                        '<td><b>Email</b></td>'+
                        '<td>'+xhr.personal.email+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Mobile</b></td>'+
                        '<td>'+xhr.personal.mobile+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Father Mobile</b></td>'+
                        '<td>'+xhr.contact.father_mobile+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Friend Mobile</b></td>'+
                        '<td>'+xhr.contact.friend_mobile+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>State</b></td>'+
                        '<td>'+xhr.contact.stateName+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>City</b></td>'+
                        '<td>'+xhr.contact.cityName+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Address</b></td>'+
                        '<td>'+xhr.contact.address+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Pincode</b></td>'+
                        '<td>'+xhr.contact.pincode+'</td>'+
                    '</tr>'+
                '</tbody>'+
                '</table>';
                $('#contacts_deatils').html(contacts);

                var bank = '<table class="table table-condensed">'+
                '<tbody>'+
                    '<tr>'+
                        '<td><b>Acc Holder Name</b></td>'+
                        '<td>'+xhr.bank.acc_holder_name+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Bank Name</b></td>'+
                        '<td>'+xhr.bank.name+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Acc Number</b></td>'+
                        '<td>'+xhr.bank.acc_number+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>IFSC Code</b></td>'+
                        '<td>'+xhr.bank.ifsc_code+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Pan Number</b></td>'+
                        '<td>'+xhr.bank.pan_number+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><b>Branch Name</b></td>'+
                        '<td>'+xhr.bank.branch_name+'</td>'+
                    '</tr>'+
                '</tbody>'+
                '</table>';
                $('#bank_deatils').html(bank);


                var education_details = xhr.education;
                var education = '';
                for(var i=0;i<education_details.length;i++){
                    var link='NA';
                    if(education_details[i].document!=null){
                        link='<a href="{{asset("employee/education")}}/'+education_details[i].document+'" download><i class="fa fa-file"></i></a>';
                    }
                    education += '<tr><td>'+education_details[i].education_title+'</td>'+
                    '<td>'+education_details[i].course_name+'</td>'+
                    '<td>'+education_details[i].board_university+'</td>'+
                    '<td>'+dateFormate(education_details[i].from_year)+'</td>'+
                    '<td>'+dateFormate(education_details[i].to_year)+'</td>'+
                    '<td>'+education_details[i].percentage_cgpa+'</td>'+
                    '<td>'+link+'</td></tr>';
                }
                $('#education_deatils').html(education);

                var companie = xhr.companies;
                var comp='';
                for(var i=0;i<companie.length;i++){
                    comp += '<tr><td>'+companie[i].comp_name+'</td>'+
                    '<td>'+companie[i].designation+'</td>'+
                    '<td>'+dateFormate(companie[i].date_of_joining)+'</td>'+
                    '<td>'+dateFormate(companie[i].date_of_resignation)+'</td>'+
                    '<td>'+companie[i].ctc+'</td>'+
                    '<td>'+companie[i].reason_for_leav_comp+'</td></tr>';
                }
                $('#companie_deatils').html(comp);

                var empdocument = xhr.emp_document;
                var docum='';
                var link1='NA';
                for(var i=0;i<empdocument.length;i++){
                    if(empdocument[i].doucment_file!=null){
                        link1='<a href="{{asset("employee/documnet")}}/'+empdocument[i].doucment_file+'" download><i class="fa fa-file"></i></a>';
                    }
                    docum += '<tr><td>'+empdocument[i].doucment_title+'</td>'+
                    '<td>'+link1+'</td>';
                }
                $('#document_deatils').html(docum);
            }
            spinner.hide();
        });
    }
    </script>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
    </script>
    @endsection('content')