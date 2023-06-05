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
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Pending hiring request</h5>
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
                                    <th>Manager Name</th>
                                    <th>Salary</th>
                                    <th>Candidate Email</th>
                                    <th>HR Name</th> 
                                    <th>Download Resume</th> 
                                    <th>Request Date</th> 
                                    <th>Upload Offer Letter</th> 
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->candidate_name}}</td>
                                    <td>{{$row->position_name}}</td>
                                    <td>{{$row->manager_name}}</td>
                                    <td>{{ucwords($row->candidate_salary)}} </td>
                                    <td>{{$row->candidate_email}}</td>
                                    <td>{{$row->hr_name}}</td>
                                    <td>@if(!empty($row->candidate_resume))<a target="_blank" class="btn btn-primary btn-xs" href="{{url('public'.$row->candidate_resume)}}" download><i class="fa fa-download"></i></a>@endif</td>
                                    <td>{{date_format(date_create($row->created_at),"d-M-Y")}}</td>
                                    <td> 
                                     <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="attach_offer_letter('{{$row->id}}');" href="#" Upload> <i class="fa fa-upload"><!-- Upload Offer Letter --></i></a>
                                    </td>
                                   <!-- attach_offer_letter -->   
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

<div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload and send offer letter to candidate</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
                 <form id="imageUploadForm" class="forms-sample row" action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="candidate_id" id="candidate_id">
                    <div class="col-sm-10">
                                <div class="multi-field-wrapper" style="margin-top: 12px;">
                                    <div class="multi-fields">
                                        <div class="multi-field">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <lable>Document Title</lable>
                                                        <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="width:40%;flex: 0 0 40%;">
                                                    <div class="form-group">
                                                        <lable>Select Document</lable>
                                                        <input type="file" name="upload_document[]" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -60px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button><br>
                                </div>

                                <div class="col-sm-4">
                                <div class="form-group" style="margin-top: 32px;"> 
                                    <button type="submit" class="btn btn-primary btn-sm  mr-2">Upload & Send</button>
                                </div>
                            </div>


                            </div>
                 </form>          
                    <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
                </div>
                 
            </div>
        </div>
    </div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<script>
function attach_offer_letter(id){  
     var candidates = $('#candidate_id').val(id)
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
$(document).ready(function (e) {
    $('#imageUploadForm').on('submit',(function(e) { 
        e.preventDefault();
        var formData = new FormData(this);
        $('#alert-image').fadeIn();
        var spinner = $('#loader');
        spinner.show();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            type:'POST',
            url: "{{url('ajax/upload-offer-letter-document')}}",
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
                    alert("Offer letter has been sent to the candidate.");
                    $('#alert_msg').html('<div class="alert alert-success">'+data.msg+'</div>');
                    $('#myModal').modal('hide');
                    $('#imageUploadForm')[0].reset();
                    spinner.hide();
                    location.reload();

                }else{
                    $('#imageUploadForm')[0].reset();
                    spinner.hide();
                    $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                    alert("This Offer Letter Already Send.");
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
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
        dom: 'Bfrtip',
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        });
    });
    function show_data(id){
        $('#candidate_id').val(id);
        $('.candidate_list').html($('#dx'+id).attr("data-name"));
        $('#exampless').dataTable().fnClearTable();
        $('#exampless').dataTable().fnDraw();
        $('#exampless').dataTable().fnDestroy();
        var datatable = $('#exampless').dataTable({
            ajax: "{{url('ajax/hiring-process-status')}}/"+id,
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'name'},
                {data:'status'},
                {data:'status_remark'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.created_at);
                    }
                }
            ]
        });
    }
</script>
 

@endsection('content')