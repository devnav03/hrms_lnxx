@extends('layouts.organization.app')
@section('content')
<style>
    .update_data td{
        border: 1px solid #80808036 !important;
        line-height:0;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    .tbl-border thead tr th{
        color: black;
        font-weight: 600;
        line-height:0;
    }
    .btn-add{
        padding: 0.5rem 0.81rem;
        background-color: #0000ffba;
        border-radius: 3px;
        color:#fff;
    }
    .btn-view{
        padding: 0.5rem 0.81rem;
        background-color: #ffbf36;
        border-radius: 3px;
    }
    .btn-danger{
        padding: 0.5rem 0.81rem;
        background-color: #f83e37;
        border-radius: 3px;
    }
    </style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('add-assets-item')}}" class="btn btn-info btn-sm mb-2"><i class="fa fa-arrow-left"></i> Go Back</a>
            </div>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Component Details</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-assets-item')}}" method="POST">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Assets Type *</label>
                                    <input type="text" name="assets_type" id="assets_type" class="form-control" value="Camera" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Model Number *</label>
                                    <input type="text" class="form-control" id="model_number" name="model_number" value="Zebronic Web Cam 04" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Serial No *</label>
                                    <input type="text" class="form-control" id="serial_no" name="serial_no" value="004" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Purchase Date *</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="11/26/2021" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Item Cost *</label>
                                    <input type="date" class="form-control" id="item_cost" name="item_cost" value="800" readonly>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea rows="2" name="description" class="form-control" readonly>Zebronic Web Cam</textarea>
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                                <div class="multi-field-wrapper">
                                    <div class="multi-fields">
                                        <div class="multi-field">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Component Name</label>
                                                        <input type="text" class="form-control" id="component_name" name="component_name[]" placeholder="Enter Component Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Warranty</label>
                                                        <input type="text" class="form-control" id="warranty" name="warranty[]" placeholder="Enter Warranty" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <textarea rows="2" name="description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Date *</label>
                                                        <input type="date" class="form-control" id="date" name="date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Attachment</label>
                                                        <input type="file" id="file_upload" name="doucment_file[]" class="form-control" onchange="return fileValidation()" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="remove-field btn-danger btn-sm float-right remove-btn" style="width:3%;margin-top: -60px;margin-right: 700px;font-size: 20px;padding: 0px;"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-field remove-field btn-info btn-sm float-right add-btn" style="margin-top: -60px;margin-right: 593px;padding: 5px;"><i class="fa fa-plus"></i> Add More</button><br>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    


    <script>
        function fileValidation() {
            var fileInput = document.getElementById('file_upload');
            var filePath = fileInput.value;
            // Allowing file type
            var allowedExtensions = /(\.doc|\.docx|\.pdf|\.txt|\.xls|\.zip)$/i;
            if (!allowedExtensions.exec(filePath)) {
                alert('Please select docx, doc, pdf, txt, xls, zip file format');
                fileInput.value = '';
                return false;
            }
        }
    </script>
    <script>
        $('.multi-field-wrapper').each(function() {
            var $wrapper = $('.multi-fields', this);
            $(".add-field", $(this)).click(function(e) {
                $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
            });
            $('.multi-field .remove-field', $wrapper).click(function() {
                if ($('.multi-field', $wrapper).length > 1)
                    $(this).parent('.multi-field').remove();
            });
        });
    </script>
    
@endsection('content')