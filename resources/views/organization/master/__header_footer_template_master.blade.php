@extends('layouts.organization.app')
@section('content')
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<style>
    .header_img {
        position: relative;
    }
    .header_img input {
        position: absolute;
        width: 100%;
        cursor: pointer;
        height: 100%;
        opacity: 0;
    }
    .header_img img {
        max-width: 340px;
    }
    .footer_img {
        position: relative;
    }
    .footer_img input {
        position: absolute;
        width: 100%;
        cursor: pointer;
        height: 100%;
        opacity: 0;
    }
    .footer_img img {
        max-width: 340px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Header Footer Template Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" id="submitform">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group header_img">
                                    <input type="file" id="imgInp" accept="image/png, image/jpeg" name="header_image" class="form-control header_footer">
                                    <img src="{{asset('organization/logo/upload_image.png')}}" id="blah" class="img-responsive ">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group footer_img">
                                    <input type="file" id="imgInp1" accept="image/png, image/jpeg" name="footer_image" class="form-control header_footer">
                                    <img src="{{asset('organization/logo/upload_image.png')}}" id="blah1" class="img-responsive">
                                </div>
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    <script src=
    "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>


    <script>
        $(document).ready(function (e) {
            $('#imgInp').on('change',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                // var header_image = $('#imgInp').prop($(this)[0]);   
                // var footer_image = $('#footer_image').prop($(this)[0]);   

                //formData.append('header_image', $(this)[0]);
                // formData.append('footer_image', footer_image);
                
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/header-template')}}",
                    type: 'POST',
                    contentType: 'multipart/form-data',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        alert('uploaded');
                    },
                    error: (response) => {
                        alert('failed');
                    }
                });
            }));
        });
    </script>


    <script type="text/javascript">
    imgInp.onchange = evt => {
    const [file] = imgInp.files
    if (file) {
        blah.src = URL.createObjectURL(file)
    }
    } 
    </script>
    <script type="text/javascript">
    imgInp1.onchange = evt => {
    const [file] = imgInp1.files
    if (file) {
        blah1.src = URL.createObjectURL(file)
    }
    } 
    </script>
@endsection('content')