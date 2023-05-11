@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Hiring Status Type</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('interview-hiring-status')}}" method="POST">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Enter Status Type Name</label>
                                    <input type="hidden" name="id" value="@if(!empty($update->id)){{$update->id}}@endif">
                                    <input type="text" class="form-control" id="status_name" name="status_name" value="@if(!empty($update->status_name)){{$update->status_name}}@endif" placeholder="Enter Status Type Name" maxlength="50" required>
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
                                    <th>Status Type</th>
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
$(document).ready(function () {
    var datatable = $('#examples').dataTable({
    dom: 'Bfrtip',
    buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    });
});
</script>
@endsection('content')