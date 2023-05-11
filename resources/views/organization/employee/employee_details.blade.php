@extends('layouts.organization.app')
@section('content')
<style>
    .table-condensed th, .table-condensed td{
        padding: 0.9375rem 0.75rem;
    }
    .modal-lg, .modal-xl {
        max-width: 1000px;
    }
    .lable-buttons{
        background: #3f9abd;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
    }
</style>
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
                    <div class="row" id="all_employee_data"></div>
                </div>
            </section>
            </div>
            <div class="modal-footer">
                <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
            </div>
        </div>
    </div>
</div>
<div id="myModal1" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title d-flex"><div id="user_details"></div>&nbsp;&nbsp; <span id="full_name"></span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body" id="reason_for_leav_comp_desc">
            <section style="background-color: #eee;">
                <div class="container py-3">
                    <div class="row">
                        <ul id="update_users"></ul>
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
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Details</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Id</th>
                                    <th>Name</th>
                                    <th>Email</th>

                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Designation</th>

                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/employee-details')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'employee_code'},
                {data:'name'},
                {data:'email'},

                {data:'office_name'},
                {data:'department_name'},
                {data:'position_name'},
                
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
                        return '<a style="margin-right:5px;" onclick="get_users_data('+data.id+')" data-toggle="modal" data-target="#myModal" type="button" class="text-primary"><i class="fa fa-eye"></i></a>' + '<a href="#" data-toggle="modal" data-target="#myModal1" data-name="'+data.name+'" onclick="get_users_id('+data.id+')" class="text-primary dx_'+data.id+'"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>'+
                        '<a href="{{url("delete-employees")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        });
    });
    function get_users_data(id){
        var spinner = $('#loader');
        spinner.show();
        $.get("{{url('ajax/get-employee-all-details')}}/"+id+"",function(xhr){
            $('#all_employee_data').html(xhr);
            spinner.hide();
        });
    }
    function get_users_id(id){
        $('#update_users').empty();
        var spinner = $('#loader');
        spinner.show();
        $.get("{{url('ajax/get-orgnisation-category')}}/"+id+"",function(xhr){
            $('#user_details').text('Update '+$('.dx_'+id).attr("data-name"));
            var datas = xhr.data;
            for (var i = 0; i < datas.length; i++) {
                $('#update_users').append('<li style="list-style: none"><a class="lable-buttons" href="{{url('update-employeess')}}/'+datas[i].id+'/'+id+'">Update '+datas[i].name+'</a></li>');
            }
            spinner.hide();
        });
    }
</script>
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
                    url: "{{url('ajax/update-users-status')}}",
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
@endsection('content')