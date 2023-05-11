@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Document Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-document-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Enter Document Name</label>
                                    <input type="hidden" name="id" value="@if(!empty($update->id)){{$update->id}}@endif">
                                    <input type="text" class="form-control" id="document_title" name="document_title" value="@if(!empty($update->document_title)){{$update->document_title}}@endif" placeholder="Enter Document Title" maxlength="50" required>
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
                                <h5 class="" id="getCameraSerialNumbers">Document Master</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Document Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            </tbody>
                            @if(!empty($result))
                                @foreach($result as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->document_title}}</td>
                                    <td>{{date_format(date_create($row->created_at),"d-M-Y H:i")}}</td>
                                    <td>
                                        <a href="{{url('update-document-master',$row->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                        <a href="{{url('delete-document-master',$row->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
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