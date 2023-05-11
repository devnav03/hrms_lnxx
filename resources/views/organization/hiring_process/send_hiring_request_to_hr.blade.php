@extends('layouts.organization.app')
@section('content')


<style>
    .header_img {
        position: relative;
    }
    .header_img input {
        position: absolute;
        width: 60%;
        cursor: pointer;
        height: 60%;
        opacity: 0;
    }
    .header_img img {
        max-width: 40px;
        min-width: 40px;
        max-height: 40px;
        min-height: 40px;
        /*border: 2px solid blue;
        padding: 5px;
        border-radius: 10px;*/
        
    }
    
</style>


<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Create hiring request</h5>
                    </div>
                    <div class="card-body">
                    <form class="forms-sample row" action="{{url('save-send-hiring-request-to-hr')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group"> 
                                <input type="hidden" class="form-control" id="emp_id" name="emp_id" value="">
                                    <label>Candidate Name </label>
                                    <input type="text" class="form-control" id="candidate_name" name="candidate_name" value="" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                            <div class="form-group"> 
                                <label>Position Name </label>
                                <select id="candidate_position" name="candidate_position" class="form-control" style="width:100%" required>
                                    <option value="">--Select--</option>
                                     @if(!empty($position))
                                     @foreach($position as $positions)
                                     <option value="{{$positions->id}}">{{$positions->position_name}}</option>
                                     @endforeach
                                     @endif   
                                </select>
                               
                            </div>
                            </div>
                              <div class="col-sm-4">
                                <div class="form-group"> 
                                    <label>Candidate Email </label>
                                    <input type="email" class="form-control" id="candidate_email" name="candidate_email" required>
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group"> 
                                    <label>Candidate Mobile No </label>
                                    <input type="text" class="form-control" id="candidate_mobile" name="candidate_mobile" required>
                                </div>
                            </div>



                            <div class="col-sm-4">
                                <div class="form-group"> 
                                    <label>Candidate Gender </label>
                                     <select id="candidate_gender" name="candidate_gender" class="form-control" style="width:100%" required>
                                        <option value="">--Select--</option>
                                        <option value="male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Salary in <b style="color:red;">*AED</b></label>
                                      <select id="candidate_salary" name="candidate_salary" class="form-control" style="width:100%" required>
                                        <option value="">--Select--</option>
                                        <option value="10-To-20 K">10-To-20 K</option>
                                        <option value="20-To-30 K">20-To-30 K</option>
                                        <option value="30-To-40 K">30-To-40 K</option>
                                        <option value="40-To-50 K">40-To-50 K</option>
                                        <option value="50-To-60 K">50-To-60 K</option>
                                        <option value="60-To-70 K">60-To-70 K</option>
                                        <option value="70-To-80 K">70-To-80 K</option>
                                        <option value="80-To-90 K">80-To-90 K</option>
                                        <option value="90-To-100 K">90-To-100 K</option>
                                    </select> 
                                </div>
                               
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select HR</label>
                                   <select id="hr_email" name="hr_email" class="form-control" style="width:100%" required>
                                    <option value="">--Select--</option>
                                    @if(!empty($hr_email_lists))
                                        @foreach($hr_email_lists as $hr_emails)
                                         <option value="{{$hr_emails->email}}">{{$hr_emails->name}} ({{$hr_emails->employee_code}})</option>
                                        @endforeach
                                    @endif
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Candidate Resume *</label>
                                    <input type="file" name="candidate_resume" id="candidate_resume" class="form-control" onchange="loadFile(event)" required>
                                </div>
                                
                            </div>





                            <div class="col-sm-4">
                                <div class="form-group" style="margin-top: 32px;"> 
                                    <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
             
        </div>
    </div>
</div>


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
$(document).ready(function () {
    var datatable = $('#examples').dataTable({
    dom: 'Bfrtip',
    buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    });
});
$(document).ready(function() {
    //$('.hr_email').select2();
    $('.employee_id').select2();
});
</script>
@endsection('content')