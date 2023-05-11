@extends('layouts.organization.app')
@section('content')
<style>
.steps {
    list-style: none;
    display: table;
    width: 100%;
    padding: 0;
    margin: 0;
    position: relative
}
.steps>li {
    text-align: center;
    width: 100%;
    position: relative;
    height: 100px;
}

.steps>li .step {
    border: 2px solid #f3546a;
    color: #546474;
    font-size: 15px;
    border-radius: 100%;
    position: absolute;
    z-index: 2;
    display: inline-block;
    width: 30px;
    height: 30px;
    background: white;
    left: 0px;
    top: 0px;
}
.steps>li:last-child:before{
    display: none;
}
.steps>li:before {
    display: block;
    content: "";
    width: 1px;
    height: 100px;
    font-size: 0;
    overflow: hidden;
    background: #f3546a;
    position: absolute;
    top: 16px;
    z-index: 1;
    left: 15px;
}
.steps>li.active .step,.steps>li.active:before,.steps>li.complete .step,.steps>li.complete:before {
    border-color: #00d082;
    background: #00d082;
    color: white;
}
.steps>li.complete .step {
    cursor: default;
    color: #FFF;
    -webkit-transition: transform ease .1s;
    -o-transition: transform ease .1s;
    transition: transform ease .1s
}
.steps>li.complete .step:before {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    border-radius: 100%;
    content: "\f00c";
    z-index: 3;
    font-family: FontAwesome;
    font-size: 17px;
    color: #87BA21
}
.step-content,.tree {
    position: relative
}
.steps>li.complete:hover .step {
    -moz-transform: scale(1.1);
    -webkit-transform: scale(1.1);
    -o-transform: scale(1.1);
    -ms-transform: scale(1.1);
    transform: scale(1.1);
    border-color: #80afd4
}
.steps>li.complete:hover:before {
    border-color: #80afd4
}
.steps>li .title {
    display: block;
    margin-top: 4px;
    max-width: 100%;
    color: #949EA7;
    font-size: 16px;
    font-weight: 600;
    z-index: 104;
    text-align: left;
    table-layout: fixed;
    word-wrap: break-word;
    padding-left:40px;
}
.steps>li.active .title,.steps>li.complete .title {
    color: #2B3D53
}
.step-content .step-pane {
    display: none;
    min-height: 200px;
    padding: 4px 8px 12px
}
.step-content .step-pane.active {
    display: block
}
.wizard-actions {
    text-align: right
}
@media only screen and (max-width: 767px) {
    .steps li .step,.steps li:after,.steps li:before {
        border-width:3px
    }
    .steps li .step {
        width: 30px;
        height: 30px;
        line-height: 24px
    }
    .steps li.complete .step:before {
        line-height: 24px;
        font-size: 13px
    }
    .steps li:before {
        top: 16px
    }
    .step-content .step-pane {
        padding: 4px 4px 6px;
        min-height: 150px
    }
}
.modal{
  pointer-events: none!important;
}
.modal-dialog{
  pointer-events: all!important;
}
.multi-field {
    margin-bottom: 25px;
}
.multi-field:before{
    display: table;
    content: " ";
}
.multi-field:after{
    display: table;
    content: " ";
}
.btnmore{
    background-color: #008d4c;
    padding: 2px 10px;
    color: white;
}
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
.cursor-pointer {cursor: pointer;}
.selection__rendered {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    list-style: none;
    margin: 0;
    padding: 0 5px;
    width: 100%;
}
.container .selection--multiple .selection__rendered {
    display: inline-block;
    overflow: hidden;
    padding-left: 8px;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.selection__rendered li {
    list-style: none;
}
.selection__choice {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    background-color: #337ab7;
    border: 1px solid #337ab7;
    border-radius: 2px;
    color: white;
    font-size: 14px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.selection__choice__remove {
    -webkit-box-ordinal-group: 2;
    -ms-flex-order: 1;
    order: 1;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-left: 5px;
}
.ink_documnet{
    text-decoration: none;
    color: white;
}
.ink_documnet:hover{
    color: #ede4e4;
    font-size: 15px;
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Onboard Candidate Documents Status ➤ {{$empdetails->salutation}} {{$empdetails->first_name}} {{$empdetails->middle_name}} {{$empdetails->last_name}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="steps" style="margin-left: 0">
                                    @if(!empty($result))
                                        @foreach($result as $row)
                                        @php $int_doc = App\Models\InterviewDocument::select('id','documnet_title','documnet_file','created_at')->where('candidate_id',$empdetails->id)->where('document_id',$row->id)->first(); @endphp
                                        <li id="lis_{{$row->id}}" @if(!empty($int_doc)) class="active" @endif>
                                            <span class="step">{{$loop->iteration}}</span>
                                            @if(!empty($int_doc))
                                                <span class="title text-success cursor-pointer dx_{{$row->id}}" data-toggle="modal" data-target="#myModal1" data-name="{{$row->status_name}}" onclick="show_data({{$row->id}},{{$empdetails->id}})">{{$row->status_name}} <i class="favicon_{{$row->id}} fa fa-check"></i> <span style="float:right;font-size: 13px;">{{date_format(date_create($int_doc->created_at),"d-M-Y H:i")}}</span></span>
                                            @else
                                                <span class="title text-danger cursor-pointer dx_{{$row->id}}" data-toggle="modal" data-target="#myModal" data-name="{{$row->status_name}}" onclick="show_data({{$row->id}},{{$empdetails->id}})">{{$row->status_name}} <i class="favicon_{{$row->id}} fa fa-upload"></i><span class="date_time_{{$row->id}}" style="float:right;font-size: 13px;"></span></span>
                                            @endif
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Upload <span class="documents_name"></span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
                <form id="imageUploadForm" method="POST">
                    <input type="hidden" name="candidate_id" value="{{$empdetails->id}}">
                    <input type="hidden" name="document_id" id="document_id">
                    <span id="alert-image"></span>
                    <div class="multi-field-wrapper">
                        <div class="multi-fields">
                            <div class="multi-field">
                                <div class="row">
                                    <div class="col-md-6">
                                        <lable>Documnet Title</lable>
                                        <input type="text" name="filename[]" class="form-control" placeholder="Enter Documnet Title" required="">
                                    </div>
                                    <div class="col-md-6" style="width:40%;flex: 0 0 40%;">
                                        <lable>Select Document</lable>
                                        <input type="file" name="upload_document[]" class="form-control" required="">
                                    </div>
                                </div>
                                <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:7%;margin-top: -35px;padding: 0.3rem 0rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button><br>
                    </div>
                    <hr>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="myModal1" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">View <span class="documents_name"></span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
            <span class="alert-remove"></span>
            <ul class="selection__rendered">
                
            </ul>
            </div>
        </div>
    </div>
</div>
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

