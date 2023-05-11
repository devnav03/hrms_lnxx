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
                        <div class="forms-sample row">
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
                        </div>

                        <hr style="margin: 30px -30px 25px;">
                        <div class="col-md-12">
                            <h4 class="text-center mb-4" style="color:#135cbb">Assets Uploaded Document</h4>
                        </div>

                        <table class="table tbl-border">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Document Title</th>
                                    <th>Uploaded Document</th>
                                    <th>Owner</th>
                                    <th>Updated Date</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody class="update_data">
                                <tr>
                                    <td>1</td>
                                    <td>Invoice</td>
                                    <td></td>
                                    <td>Organisation Admin</td>
                                    <td>2022-01-15 07:26:35</td>
                                </tr>
                            </tbody>
                        </table>


                        <hr style="margin: 30px -30px 25px;">
                        <div class="col-md-12">
                            <h4 class="text-center mb-4" style="color:#135cbb">Component Details</h4>
                        </div>

                        <table class="table tbl-border">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Component Name</th>
                                    <th>Warranty</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Attachment</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody class="update_data">
                                <tr>
                                    <td>1</td>
                                    <td>Mouse</td>
                                    <td>1</td>
                                    <td>2023-01-09</td>
                                    <td>For Mouse</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        
    </div>
    

@endsection('content')