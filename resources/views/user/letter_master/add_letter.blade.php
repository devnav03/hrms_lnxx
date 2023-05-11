@extends('layouts.user.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Create Letter</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-letter')}}" method="POST">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Letter Name</label>
                                    <input type="text" class="form-control" id="letter_name" name="letter_name" placeholder="Letter Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Mode</label>
                                    <select class="form-control" id="mode" name="mode" required>
                                        <option value="">Select</option>
                                        <option value="Manually">Manually</option>
                                        <option value="Auto">Auto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
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
        </div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript">
    $(function () {
        $("#letter_name").keypress(function (e) {
            if(e.which === 32) 
                return true;
            var keyCode = e.keyCode;
            $("#letterNameError").html("");
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z]+$/;

            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#letterNameError").html("Only Alphabets allowed.");
            }
            return isValid;
        });
    });
</script>
    @endsection('content')