@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Hiring Status Approval</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('interview-hiring-status-approval')}}" method="POST">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Select Status Type Name</label>
                                    <input type="hidden" name="id" value="@if(!empty($update->id)){{$update->id}}@endif">
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
                                    <label>Office Name</label>
                                    <select id="office_id" onchange="get_office()" name="office_id[]" class="form-control office_id" multiple required>
                                    <option value="">--Select--</option>
                                    @if(!empty($office))
                                        @foreach($office as $row)
                                        <option value="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select id="employee_id" name="employee_id[]" class="form-control employee_id" multiple required>
                                    <option value="">--Select--</option>
                                    <!-- @if(!empty($office))
                                        @foreach($office as $row)
                                        <option value="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                        @endforeach
                                    @endif -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
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
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Hiring Status List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            </tbody>
                            @if(!empty($result))
                                @foreach($result as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->status_name}}</td>
                                    <td>{{date_format(date_create($row->created_at),"d-M-Y H:i")}}</td>
                                    <td>
                                        <a href="{{url('update-hiring-status',$row->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                        <a href="{{url('delete-hiring-status',$row->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                    </td>
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
</script>
@endsection('content')