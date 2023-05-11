@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Form Category</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('form-category-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Category Name *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="text" class="form-control" id="name" name="name" value="@if(!empty($update->name)){{$update->name}}@endif" placeholder="Enter Category Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <!--<div class="col-sm-4">-->
                            <!--    <div class="form-group">-->
                            <!--        <label>Select Group Name</label>-->
                            <!--        <select class="form-control" id="is_multiple" name="is_multiple" required>-->
                            <!--            <option value="">Select</option>-->
                            <!--            <option value="0" @if(!empty($update->is_multiple)) @if($update->is_multiple=="0") selected @endif @endif>Single</option>-->
                            <!--            <option value="1" @if(!empty($update->is_multiple)) @if($update->is_multiple=="1") selected @endif @endif>Multiple</option>-->
                            <!--        </select>-->
                            <!--    </div>-->
                            <!--</div>-->
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
                                <h5 class="" id="">Form Category List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Group Name</th>
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
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-form-engine-masters')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'name'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        if(data.is_multiple=='0'){
                            return '<td><i data="'+data.id+'" id="indss'+data.id+'" class="status_checks btn-xs btn btn-outline-success">Single</i></td>';
                        }else{
                            return '<td><i data="'+data.id+'" id="indss'+data.id+'" class="status_checks btn-xs btn btn-outline-danger">Multiple</i></td>';
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
                        return '<a href="{{url("form-category-master")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("form-category-master-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
    $(document).on('click','.status_checks',function(){
        var is_multiple = ($(this).hasClass("btn-outline-success")) ? '1':'0';
        var msg = (is_multiple=='1') ? 'Multiple' : 'Single';
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
                    url: "{{url('ajax/get-status-form')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),is_multiple:is_multiple},
                    success: function(xhr){
                        if(xhr.data.is_multiple=='1'){
                            $('#indss'+xhr.data.id).addClass('btn-outline-danger');
                            $('#indss'+xhr.data.id).removeClass('btn-outline-success');
                            $('#indss'+xhr.data.id).text('Multiple');
                        }else{
                            $('#indss'+xhr.data.id).addClass('btn-outline-success');
                            $('#indss'+xhr.data.id).removeClass('btn-outline-danger');
                            $('#indss'+xhr.data.id).text('Single');
                        }
                        swal(msg, "Succesfully "+msg, "success");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Something Went to Wrong!", "Please try again", "error");
                    }
                });
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $("#name").keypress(function (e) {
            if(e.which === 32) 
                return true;
            var keyCode = e.keyCode;
            $("#letterNameError").html("");
            var regex = /^[A-Za-z]+$/;
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#letterNameError").html("Only Alphabets allowed.");
            }
            return isValid;
        });
    });
</script>
@endsection('content')