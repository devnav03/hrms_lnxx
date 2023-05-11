@extends('layouts.organization.app')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Field</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-form')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Form Category *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="form_category_id" name="form_category_id" required>
                                        <option value="">--Select--</option>
                                        <?php if(!empty($category)) foreach($category as $cat) {?>
                                            <option value="<?=$cat->id;?>" <?php if(!empty($update->form_category_id)){ if($update->form_category_id==$cat->id){ echo 'selected'; }};?>><?=$cat->name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Type of Form *</label>
                                    <select class="form-control" id="data_type" name="data_type" required>
                                        <option value="">--Select--</option>
                                        <option value="text" <?php if(!empty($update->data_type)){ if($update->data_type=='text'){ echo 'selected'; }};?>>Text</option>
                                        <option value="date" <?php if(!empty($update->data_type)){ if($update->data_type=='date'){ echo 'selected'; }};?>>Date</option>
                                        <option value="file" <?php if(!empty($update->data_type)){ if($update->data_type=='file'){ echo 'selected'; }};?>>File</option>
                                        <option value="email" <?php if(!empty($update->data_type)){ if($update->data_type=='email'){ echo 'selected'; }};?>>Email</option>
                                        <option value="select" <?php if(!empty($update->data_type)){ if($update->data_type=='select'){ echo 'selected'; }};?>>Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Form Name *</label>
                                    <input type="text" class="form-control" id="form_name" name="form_name" value="@if(!empty($update->form_name)){{$update->form_name}}@endif" placeholder="Enter Form Name" maxlength="70" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Group *</label>
                                    <select class="form-control" id="group_name" name="group_name" required>
                                        <option value="">--Select--</option>
                                        <?php foreach(range('A','Z') as $letter) {?>
                                            <option value="<?=$letter;?>" <?php if(!empty($update->group_name)){ if($update->group_name==$letter){ echo 'selected'; }};?>><?=$letter;?></option>
                                        <?php }?>
                                    </select>
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
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Field Details</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Drag To</th>
                                    <th>Sr No</th>
                                    <th>Category Name</th>
                                    <th>Type of Field</th>
                                    <th>Field Name</th>
                                    <th>Group</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <body id="tablecontents">
                                @if(!empty($result))
                                    @foreach($result as $rows)
                                        <tr class="row1" data-id="{{ $rows->id }}">
                                            <td class="pl-3"><i class="fa fa-sort"></i></td>
                                            <td>{{$rows->order_id}}</td>
                                            <td>{{$rows->name}}</td>
                                            <td>{{$rows->form_name}}</td>
                                            <td>{{$rows->data_type}}</td>
                                            <td>{{$rows->group_name}}</td>
                                            <td>
                                                <a href="{{url('add-form',$rows->id)}}" class="text-primary mx-1"><i class="fa fa-edit"></i></a>
                                                @if($rows->is_fixed==1)
                                                    <a href="#" class="text-danger disabled"><i class="fa fa-trash"></i></a>
                                                @else
                                                    <a href="{{url('delete-form',$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                                @endif
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

    
<script>
    function get_office_id() {
        var department_id = $('#office_id option:selected').data('id');
        $('#department_id').empty();
        $('#designation_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-department-name')}}",
            data: {
                department_id: department_id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#department_id').append('<option value="">Select Department</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
            }
        });
    }
    function get_designation() {
        var office_id = $('#office_id option:selected').data('id');
        var department_id = $('#department_id option:selected').data('id');
        $('#position_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-designation')}}",
            data: {
                office_id: office_id,
                department_id: department_id,
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#position_id').append('<option value="">Select Designation</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                }
            }
        });
    }
</script>
<script>
    $(document).on('click', '.disabled', function(){
        toastr.error("Fixed Data cant be deleted");
    });
</script>
<script>
    CKEDITOR.replace( 'description' );
</script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
@push('body-scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
<script type="text/javascript">
$(function () {
    $("#table").DataTable();
    $( "#tablecontents" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        update: function() {
            sendOrderToServer();
        }
    });
    function sendOrderToServer() {
        var order = [];
        var token = $('meta[name="csrf_token"]').attr('content');
        $('tr.row1').each(function(index,element) {
            order.push({
                id: $(this).attr('data-id'),
                position: index+1
            });
        });
        $.ajax({
        type: "POST", 
        dataType: "json", 
        url: "{{url('ajax/post-sortable')}}",
            data: {
            order: order,
            _token: token
        },
        success: function(response) {
            if (response.status == "success") {
                console.log(response);
                location.reload();
            } else {
                console.log(response);
            }
        }
        });
    }
});
</script>
@endpush
@endsection('content')