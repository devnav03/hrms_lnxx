@extends('layouts.organization.app')
@section('content')
<style>
    /* .border-set{
        border: 1px solid #ebe2e2;
        display: flex;
        padding: 13px;
    } */
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Inward Outward</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('assets-inward-outward')}}" method="POST">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select Date *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Vendor Name *</label>
                                    <select class="form-control" id="vendors_id" onclick="select_asset()" name="vendors_name">
                                        <option value="">Select</option>
                                        <option value="Shailers Solution Pvt Ltd">Shailers Solution Pvt Ltd</option>
                                        <option value="EI Network Pvt Ltd">EI Network Pvt Ltd</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 border-set">
                                <hr style="margin: 6px -30px 16px;">
                                <div class="multi-field-wrapper">
                                    <div class="multi-fields">
                                        <div class="multi-field">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Assets Name</label>
                                                        <select name="assets_type[]" class="form-control" id="assets_type">
                                                            <option value="">Select</option>
                                                            <option value="Camera">Camera</option>
                                                            <option value="RAM Upgrade">RAM Upgrade</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Brand Name</label>
                                                        <select name="brand_name[]" class="form-control" id="brand_name">
                                                            <option value="">Select</option>
                                                            <option value="Camera">Camera</option>
                                                            <option value="RAM Upgrade">RAM Upgrade</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Quantity</label>
                                                        <input type="text" name="quantity[]" class="form-control" id="quantity" placeholder="Enter Quantity">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Modal No.</label>
                                                        <input type="text" name="modal_no[]" class="form-control" id="modal_no" placeholder="Enter Modal No.">
                                                    </div>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-group">
                                                        <label>Description *</label>
                                                        <textarea rows="2" name="description" class="form-control" required="" placeholder="Enter Description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="remove-field btn-danger btn-sm float-right remove-btn" style="width:3%;margin-top: -60px;margin-right: 200px;font-size: 20px;padding: 0px;"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-field remove-field btn-info btn-sm float-right add-btn" style="margin-top: -60px;margin-right: 94px;padding: 5px;"><i class="fa fa-plus"></i> Add More</button><br>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr style="margin: 6px -30px 16px;">
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Challan Number *</label>
                                    <input type="text" name="challan_number" class="form-control" id="challan_number" placeholder="Enter Challan Number">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Receiver Person *</label>
                                    <select class="form-control" id="receiver_person" name="receiver_person">
                                        <option value="">Select</option>
                                        <option value="Ashutosh Pathak">Ashutosh Pathak</option>
                                        <option value="Dipanshu Roy">Dipanshu Roy</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Challan Attachment *</label>
                                    <input type="file" name="challan_attachment" class="form-control" id="challan_attachment">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Invoice Attachment *</label>
                                    <input type="file" name="invoice_attachment" class="form-control" id="invoice_attachment">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Note *</label>
                                    <textarea rows="2" name="description" class="form-control" required="" placeholder="Enter Description"></textarea>
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