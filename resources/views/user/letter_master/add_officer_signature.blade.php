@extends('layouts.user.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Create Officer With Signature</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-officer-signature')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Officer Name</label>
                                    <input type="text" class="form-control" id="officer_name" name="officer_name" placeholder="Officer Name" required>
                                    <span id="officerNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Upload Signature Image</label>
                                    <input type="file" name="signature" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Signature Image">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-info" type="button" style="padding: 7px;">Upload</button>
                                    </span>
                                    </div>
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
        $("#officer_name").keypress(function (e) {
            if(e.which === 32) 
                return true;
            var keyCode = e.keyCode || e.which;
            $("#officerNameError").html("");
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z]+$/;

            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#officerNameError").html("Only Alphabets allowed.");
            }
            return isValid;
        });
    });
</script>
    @endsection('content')