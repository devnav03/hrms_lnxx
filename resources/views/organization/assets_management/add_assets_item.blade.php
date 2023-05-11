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
        color:#fff;
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
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Assets Item</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-assets-item')}}" method="POST">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Assets Type *</label>
                                    <select name="assets_type" id="assets_type" class="form-control">
                                        <option>Select Assets Type</option>
                                        <option value="cables">cables</option>
                                        <option value="Camera">Camera</option>
                                        <option value="CPU">CPU</option>
                                        <option value="Desktop">Desktop</option>
                                        <option value="Keyboard">Keyboard</option>
                                        <option value="Laptop">Laptop</option>
                                        <option value="Laptop Adapter">Laptop Adapter</option>
                                        <option value="Mobile">Mobile</option>
                                        <option value="Monitor">Monitor</option>
                                        <option value="Mouse">Mouse</option>
                                        <option value="RAM Upgrade">RAM Upgrade</option>
                                        <option value="UPS">UPS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Model Number *</label>
                                    <input type="text" class="form-control" id="model_number" name="model_number" placeholder="Enter Model Number" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Serial No *</label>
                                    <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Serial No" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Purchase Date *</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Item Cost *</label>
                                    <input type="date" class="form-control" id="item_cost" name="item_cost" placeholder="Enter Item Cost">
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea rows="2" name="description" class="form-control" required=""></textarea>
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                                <h4 class="text-center mb-4" style="color:#135cbb">Assets Upload Document</h4>
                                <div class="multi-field-wrapper">
                                    <div class="multi-fields">
                                        <div class="multi-field">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Document Title</label>
                                                        <input type="text" class="form-control" id="doucment_title" name="doucment_title[]" placeholder="Doucment Title" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Document File</label>
                                                        <input type="file" id="file_upload" name="doucment_file[]" class="form-control" onchange="return fileValidation()" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="remove-field btn-danger btn-sm float-right remove-btn" style="width:3%;margin-top: -60px;margin-right: 126px;font-size: 20px;padding: 0px;"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-field remove-field btn-info btn-sm float-right add-btn" style="margin-top: -60px;margin-right: 0px;padding: 5px;"><i class="fa fa-plus"></i> Add More</button><br>
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

        <div class="row mt-4">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Item List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Assets Type</th>
                                    <th>Model Number</th>
                                    <th>Serial No</th>
                                    <th>Purchase Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>Camera</td>
                                    <td>Zebronic Web Cam 04	</td>
                                    <td>004</td>
                                    <td>11/26/2021</td>
                                    
                                    <td><a class="btn btn-success btn-sm">Active</td>
                                    <td>
                                        <a href="{{url('add-component')}}" class="btn btn-sm btn-add"><i class="fa fa-plus-square-o"></i></a>
                                        <a href="{{url('view-assets-item')}}" class="btn btn-sm btn-view"><i class="fa fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>RAM Upgrade</td>
                                    <td>16gb DDR3</td>
                                    <td>001</td>
                                    <td>01/15/2022</td>
                                    
                                    <td><a class="btn btn-danger btn-sm">Deactive</td>
                                    <td>
                                        <a href="{{url('add-component')}}" class="btn btn-sm btn-add"><i class="fa fa-plus-square-o"></i></a>
                                        <a href="{{url('view-assets-item')}}" class="btn btn-sm btn-view"><i class="fa fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>

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