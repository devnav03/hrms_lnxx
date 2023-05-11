@extends('layouts.organization.app')
@section('content')
<style>
    #limheight {
    height: auto;
    -webkit-column-count: 1;
       -moz-column-count: 1;
            column-count: 1;
            
    line-height: 2;

}
.single-article hr {
    margin: 20px -31px 12px;
    border: 0;
    border-top: 1px solid #c9c7c7;
}
</style>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add SMS Template</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-sms-template')}}" method="POST">
                            @csrf
                                <div class="col-sm-12 single-article">
                                    <label>Variable Name *</label>
                                    

                                    <div id="limheight" class="mt-2">
                                        <span class="label label-primary copy_to_clip">employer_name</span>
                                        <span class="label label-primary copy_to_clip">empployee_code</span>
                                        <span class="label label-primary copy_to_clip">officer_name</span>
                                        <span class="label label-primary copy_to_clip">header_template</span>
                                        <span class="label label-primary copy_to_clip">footer_template</span>
                                    </div>
                                    <hr>
                                </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Template Title *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="text" class="form-control" id="template_title" name="template_title" value="@if(!empty($update->template_title)){{$update->template_title}}@endif" placeholder="Enter Template Title" required>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-12">
                                <label class="text-danger">Note: If you want to insert variable name in the template, click to copy and paste. Ex. {employee_name}</label>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea type="hiden" name="description" required>@if(!empty($update->description)){{$update->description}}@endif</textarea>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="">SMS Template List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Template Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
    $(document).on('click','.status_checks',function(){
        var status = ($(this).hasClass("btn-outline-success")) ? 'Inactive' : 'Active';
        var msg = (status=='Active')? 'Active' : 'Inactive';
        var current_element = $(this);
        swal({
            title: "Are you sure?",
            text: "Do you want to change status "+msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change status!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/get-status-sms-template')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),status:status},
                    success: function(xhr){
                        if(xhr.data.status=='Inactive'){
                            $('#inds'+xhr.data.id).addClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-success');
                            $('#inds'+xhr.data.id).text('Inactive');
                            // toastr.success("Inactive");
                        }else{
                            $('#inds'+xhr.data.id).addClass('btn-outline-success');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).text('Active');
                            // toastr.success("Active");
                        }
                        swal(xhr.data.status, "Succesfully "+xhr.data.status, "success");
                        
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Something Went to Wrong!", "Please try again", "error");
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-sms-template-list')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'template_title'},
                {data:'description'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.status=='Active'){
                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-success">Active</i></td>';
                        }else{
                            return '<td><i data="'+data.id+'" id="inds'+data.id+'" class="status_checks btn-xs btn btn-outline-danger">Inactive</i></td>';
                        }
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.created_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.updated_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("add-sms-template")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("sms-template-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ]
        });
    });
</script>

<script>
    jQuery(function () {
        $('.copy_to_clip').click(function () {
            var text = $(this).text();
            var text1 = '{'+text+'}';
            navigator.clipboard.writeText(text1);
        });
    });
</script>

    <script>
        CKEDITOR.replace( 'description' );
    </script>
@endsection('content')