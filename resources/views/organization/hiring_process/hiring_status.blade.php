@extends('layouts.organization.app')
@section('content')
<style>
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
                    <div class="card-header">
                        <h5 class="">Hiring Status ? {{$empdetails->salutation}} {{$empdetails->first_name}} {{$empdetails->middle_name}} {{$empdetails->last_name}}</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('hiring-status-approval')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Select Status Type Name</label>
                                    <input type="hidden" name="id" value="@if(!empty($update->id)){{$update->id}}@endif">
                                    <input type="hidden" name="candidate_id" value="{{$empdetails->id}}">
                                    <select id="status_id" name="status_id" class="form-control status_id" required>
                                    <option value="">--Select--</option>
                                    @if(!empty($result))
                                        @foreach($result as $row)
                                        <option value="{{$row->id}}" @if(!empty($update->status_id)) @if($update->status_id==$row->id) selected @endif @endif>{{$row->status_name}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select name="approval" class="form-control approval" required>
                                    <option value="">--Select--</option>
                                        <option value="1" @if(!empty($update->status_id)) @if($update->status_id==$row->id) selected @endif @endif>Approved</option>
                                        <option value="2" @if(!empty($update->status_id)) @if($update->status_id==$row->id) selected @endif @endif>Rejected</option>
                                        <option value="3" @if(!empty($update->status_id)) @if($update->status_id==$row->id) selected @endif @endif>Forwarded</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 show_hide_office" style="display:none">
                                <div class="form-group">
                                    <label>Office Name</label>
                                    <select id="office_id" name="office_id[]"  onchange="get_office()" class="form-control office_id" multiple style="width:100%">
                                    <option value="" disabled>--Select--</option>
                                    @if(!empty($office))
                                        @foreach($office as $row)
                                        <option value="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 show_hide_office" style="display:none">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select id="employee_id" name="employee_id[]" class="form-control employee_id" multiple style="width:100%">
                                    <option value="">--Select--</option>
                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
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
                            </div>
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-top: 15px;"> 
                                    <button type="submit" class="btn btn-primary btn-sm  mr-2">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="">Hiring Status List ? {{$empdetails->salutation}} {{$empdetails->first_name}} {{$empdetails->middle_name}} {{$empdetails->last_name}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status Type Name</th>
                                    <th>Status</th>
                                    <th>Office Name</th>
                                    <th>Employee Name</th>
                                    <th>Document Download</th>
                                    <th>Approved By & Updated On</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <body>
                                @if(!empty($rowdata))
                                    @foreach($rowdata as $rows)
                                    @php $user_id = Auth::user()->id; @endphp
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->status_name}}</td>
                                            <td>@if($rows->status==1) Approved @elseif($rows->status==2) Rejected @elseif($rows->status==3) Forwarded @endif</td>
                                            <td>
                                            <?php 
                                                if(!empty($rows->office_id)){
                                                    $office = App\Models\OfficeMaster::select('id','office_name')->whereIn('id',explode(',',$rows->office_id))->where('status','Active')->get();
                                                    if(!empty($office)){
                                                        foreach($office as $off){ 
                                                            echo '<span class="btn show-amazing">'.$off->office_name.'</span>';
                                                        } 
                                                    }
                                                }else{
                                                    echo '------------';
                                                }
                                            ?>
                                            </td>
                                            <td>
                                            <?php
                                                if(!empty($rows->employee_id)){
                                                    $emp = DB::select("SELECT b.id,a.employee_code,b.name FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code is NOT null AND a.organisation_id=$user_id AND b.id in ($rows->employee_id)");
                                                    if(!empty($emp)){
                                                        foreach($emp as $emp){ 
                                                        echo '<span class="btn show-amazing">'.$emp->employee_code.' - '.$emp->name.'</span>';
                                                    } }
                                                }else{
                                                    echo '------------';
                                                }
                                            ?>
                                            </td>
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
                                                <?php if(!empty($rows->approved_by)){
                                                    $emp1 = DB::select("SELECT b.id,a.employee_code,b.name FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code is NOT null AND a.organisation_id=$user_id AND b.id=$rows->approved_by LIMIT 1");
                                                    if(!empty($emp1)){
                                                        foreach($emp1 as $emps){ 
                                                        echo '<span class="btn show-amazing">'.$emps->employee_code.' - '.$emps->name.' <br/> Updated On '.date_format(date_create($row->updated_at),"d-M-Y H:i").'</span>';
                                                    } }
                                                }?>
                                            </td>
                                            <td>{{date_format(date_create($row->created_at),"d-M-Y H:i")}}</td>
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
    $('.office_id').select2();
    $('.employee_id').select2();
});
$(document).ready(function() {
    $(".approval").change(function(){
        if($(this).val()==3){
            $('.show_hide_office').show();
        }else{
            $('.show_hide_office').hide();
        }
    });
});
</script>
<script>
function get_office(){
    $('#employee_id').empty();
    $.ajax({
        type: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        url: "{{url('ajax/employee-against-user')}}",
        data: {office_id: $('#office_id').val()},
        success: function(xhr) {
            var datas = xhr.users;
            $('#employee_id').append('<option value="">--Select--</option>');
            for (var i = 0; i < datas.length; i++) {
                $('#employee_id').append('<option value="'+datas[i].id+'">'+datas[i].employee_code+' - '+datas[i].name+'</option>');
            }
        }
    });
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
    function show_data(id,candidate_id){
        $('#document_id').val(id);
        $('.selection__rendered').empty();
        $('.documents_name').text($('.dx_'+id).attr("data-name"));
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            type:'POST',
            url: "{{url('ajax/get-uploaded-document-status')}}",
            data:{document_id:id,candidate_id:candidate_id},
            success:function(xhr){
                if(xhr.status==200){
                    var datas = xhr.data;
                    for(var i=0;i<datas.length;i++){
                        $('.selection__rendered').append('<li class="selection__choice choice__remove_'+datas[i].id+'" title="'+datas[i].documnet_title+' Download"><span class="selection__choice__remove" onclick="remove_data('+datas[i].id+','+candidate_id+','+id+')">×</span><a href="{{url("public/uploads/status_document")}}/'+datas[i].documnet_file+'" download class="ink_documnet">'+datas[i].documnet_title+'</a></li>');

                        $('.rendered').append('<li class="choice__remove_'+datas[i].id+'" title="'+datas[i].documnet_title+' Download"><span class="selection__choice__remove" onclick="remove_data('+datas[i].id+','+candidate_id+','+id+')">×</span><a href="{{url("public/uploads/status_document")}}/'+datas[i].documnet_file+'" download class="ink_documnet">'+datas[i].documnet_title+'</a></li>');
                    }
                }
            }
        });
    }
    function remove_data(id,candidate_id,document_id){
        swal({
            title: "Are you sure?",
            text: "Do you want to remove",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change status!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $('.choice__remove_'+id).hide();
                $('.alert-remove').html('<div class="alert alert-success">Removed Successfully</div>');
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/remove-documet')}}",
                    type: "POST",
                    data: {id:id,candidate_id:candidate_id,document_id:document_id},
                    success: function(xhr){
                        if(xhr.count==0){
                            $('#lis_'+document_id).removeClass('active');
                            $('.dx_'+document_id).addClass('text-danger');
                            $('.dx_'+document_id).removeClass('text-success');
                            $('.favicon_'+document_id).addClass('fa-upload');
                            $('.favicon_'+document_id).removeClass('fa-check');
                            $('.dx_'+document_id).removeAttr('data-target','#myModal1');
                            $('.dx_'+document_id).attr('data-target','#myModal');
                            $('.date_time_'+document_id).empty();
                        }
                        swal(xhr.msg, "Succesfully "+xhr.msg, "success");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Something Went to Wrong!", "Please try again", "error");
                    }
                });
            }
        });
    }
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
            url: "{{url('ajax/upload-status-document')}}",
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                if(data.status==200){
                    $('#imageUploadForm')[0].reset();
                    $('#alert-image').html('<div class="alert alert-success">'+data.msg+'</div>');
                    $('#lis_'+data.document_id).addClass('active');
                    $('.dx_'+data.document_id).removeClass('text-danger');
                    $('.dx_'+data.document_id).addClass('text-success');
                    $('.favicon_'+data.document_id).removeClass('fa-upload');
                    $('.favicon_'+data.document_id).addClass('fa-check');
                    $('.dx_'+data.document_id).removeAttr('data-target','#myModal');
                    $('.dx_'+data.document_id).attr('data-target','#myModal1');
                    $('.date_time_'+data.document_id).text(data.data.createdat);
                }else{
                    $('#alert-image').html('<div class="alert alert-danger">'+data.msg+'</div>');
                }
                setTimeout(function () {
                    $('#alert-image').fadeOut();
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
@endsection('content')
