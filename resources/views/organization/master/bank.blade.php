@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Bank</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('bank-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Bank Name *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="text" class="form-control" id="name" name="name" value="@if(!empty($update->name)){{$update->name}}@endif" placeholder="Enter bank Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
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
                                <h5 class="" id="">Bank List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Bank Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if(!empty($result))
                                    @foreach($result as $rows)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->name}}</td>
                                            <td>{{date('d-M-Y', strtotime($rows->created_at))}}</td>
                                            <td>
                                                <a href="{{url("bank-master",$rows->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                                <a href="{{url("delete-bank",$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
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
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
	  dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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