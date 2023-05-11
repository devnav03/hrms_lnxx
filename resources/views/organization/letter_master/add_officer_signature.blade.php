@extends('layouts.organization.app')
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Officer Name</label>
                                    <input type="text" class="form-control" id="officer_name" name="officer_name" placeholder="Officer Name" required>
                                    <span id="officerNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Upload Signature Image</label>
                                    <input type="file" name="signature" class="file-upload-default" required>
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

        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Signature List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Officer Name</th>
                                    <th>Signature</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
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
            ajax: "{{url('ajax/user-officer-signature-list')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'officer_name'},
                {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a target="_blank" href="{{asset("employee/signature")}}/'+data.signature+'" ><img src="{{asset("employee/signature")}}/'+data.signature+'" style="width: 2.187rem;height: 2.187rem;border-radius: 0.25rem;object-fit: contain"/></a>';
                        }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateTimeFormate(data.created_at);
                    }
                },
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateTimeFormate(data.updated_at);
                    }
                }
            ]
        });
        setInterval(function(){
            $('#example').DataTable().ajax.reload(); 
        },3000);
    });
</script>
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