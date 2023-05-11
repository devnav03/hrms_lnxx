@extends('layouts.organization.app')
@section('content')
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<style>
    .header_img {
        position: relative;
    }
    .header_img input {
        position: absolute;
        width: 100%;
        cursor: pointer;
        height: 100%;
        opacity: 0;
    }
    .header_img img {
        max-width: 340px;
        min-width: 340px;
        max-height: 340px;
        min-height: 340px;
        /*border: 2px solid blue;
        padding: 5px;
        border-radius: 10px;*/
        
    }
    .footer_img {
        position: relative;
    }
    .footer_img input {
        position: absolute;
        width: 100%;
        cursor: pointer;
        height: 100%;
        opacity: 0;
    }
    .footer_img img {
        max-width: 340px;
        min-width: 340px;
        max-height: 340px;
        min-height: 340px;
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
                        <h5 class="">Header Footer Template Master</h5>
                    </div>
                    <div class="card-body">
  <form class="forms-sample row" action="{{url('header-footer-template-master')}}" id="submitform" method="POST" enctype="multipart/form-data">
                            @csrf
                             <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Office Name *</label>
                                   <select class="form-control" id="office_id" name="office_id" required>
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->address}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select> 
                                </div>
                            </div>



                            <div class="col-sm-4"> <label>Header Image *</label>
                                <div class="form-group header_img">
                                    <input type="file" id="imgInp" accept="image/png, image/jpeg" name="header_image" class="form-control header_footer">
                                   
                                    <img src="{{asset('organization/logo/upload_image.png')}}" id="blah" class="img-responsive ">
                                </div>
                            </div>
                            <div class="col-sm-4"><label>Footer Image *</label>
                                <div class="form-group footer_img">
                                    <input type="file" id="imgInp1" accept="image/png, image/jpeg" name="footer_image" class="form-control header_footer">
                                   
                                    <img src="{{asset('organization/logo/upload_image.png')}}" id="blah1" class="img-responsive">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                            @if(!empty($update))
                            <div class="col-sm-6">
                                <a href="{{url('header-footer-template-master')}}" class="btn btn-primary btn-sm">Back</a>
                            </div>
                            @endif
                            
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
                                <h5 class="" id="getCameraSerialNumbers">Header Footer List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office Name</th>
                                    <th>Header</th>
                                    <th>Footer</th>
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

<div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Header Footer Template</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Office Name</th>
                                <th scope="col">Header</th>
                                <th scope="col">Footer</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Updated At</th>
                            </tr>
                        </thead>
                        <tbody id="leave_data">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="leave_id">
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function (e) {
            $('#imgInp').on('change',(function(e) { //alert('call');
                e.preventDefault();
                var formData = new FormData(this);
                // var header_image = $('#imgInp').prop($(this)[0]);   
                // var footer_image = $('#footer_image').prop($(this)[0]);   

                //formData.append('header_image', $(this)[0]);
                // formData.append('footer_image', footer_image);
                
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/header-template')}}",
                    type: 'POST',
                    contentType: 'multipart/form-data',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (response) => { 
                        alert('uploaded');
                    },
                    error: (response) => {
                        alert('failed');
                    }
                });
            }));
        });
    </script>


    <script type="text/javascript">
    imgInp.onchange = evt => {
    const [file] = imgInp.files
    if (file) {
        blah.src = URL.createObjectURL(file)
    }
    } 
    </script>
    <script type="text/javascript">
    imgInp1.onchange = evt => {
    const [file] = imgInp1.files
    if (file) {
        blah1.src = URL.createObjectURL(file)
    }
    } 
    </script>
<!-- ---------------NEW CODE ADDING HERE VIKAS------------------ -->



<!-- -----MODAL DATA ON POPUP---------- -->
<script>
        function show_data(id){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/view-header-footer-data')}}", 
                data: {id:id},
                async : false,
                success:function(xhr){
                    if(xhr.status==200){
                        var html='<tr>'+
                        '<td>'+xhr.data.office_name+'</td>'+
                        '<td><img src="{{asset('organization/header_image')}}/'+xhr.data.header_image+'" height="350 !important;" width="500 !important;"></td>'+
                        '<td><img src="{{asset('organization/footer_image')}}/'+xhr.data.footer_image+'" height="350px !important;" width="500px !important;"></td>'+
                        '<td><a class="lable-primary">'+xhr.data.status+'</a></td>'+
                        '<td>'+xhr.data.created_at+'</td>'+
                        '<td>'+xhr.data.updated_at+'</td>'+
                        '</tr>';
                        $('#leave_data').html(html);
                        $('#leave_id').html('<span class="btn btn-info btn-sm" data-dismiss="modal">Close</span>');
                        spinner.hide();
                    }
                }
            });
        }
    </script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- ----MAIN DATA LISTING---------- -->
<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-header-footer-template-masters')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'office_name'},
               
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<img src="{{asset('organization/header_image')}}/'+data.header_image+'" class="img-responsive" height="70px;" width="70px;">';
                    }
                }, 
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<img src="{{asset('organization/footer_image')}}/'+data.footer_image+'" class="img-responsive " height="70px;" width="70px;">';
                    }
                },
                <!--{data:'status'},-->

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
                        return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="text-primary"><i class="fa fa-eye"></i></a>'+'<a href="{{url("header-footer-template-master")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("header-footer-template-master-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<!-- CHANGE STATUS CODE---- -->

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
                    url: "{{url('ajax/get-status-header-footer-template')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),status:status},
                    success: function(xhr){ //alert(data);
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






















@endsection('content')