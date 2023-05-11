@extends('layouts.organization.app')
@section('content')
<style>
    .hando{
        font-size: 0.875rem;
        line-height: 1.4rem;
        vertical-align: top;
        margin-bottom: 0.5rem;
        color: #000000ab;
    }
    .radio-inline{
        padding: 0px 9px 0px 2px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Outward Assets Entry</h5>
                    </div>
                    <div class="card-body">
                    <form class="forms-sample row" action="{{url('outward-assets-list')}}" method="POST">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Vendor Name *</label>
                                    <select class="form-control" id="vendors_name" onclick="select_asset()" name="vendors_name">
                                        <option value="">Select</option>
                                        <option value="Shailers Solution Pvt Ltd">Shailers Solution Pvt Ltd</option>
                                        <option value="EI Network Pvt Ltd">EI Network Pvt Ltd</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Issue *</label>
                                    <textarea rows="2" name="description" class="form-control" required="" placeholder="Enter Description"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea rows="2" name="description" class="form-control" required="" placeholder="Enter Description"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Attachment *</label>
                                    <input type="file" name="attachment" class="form-control" id="attachment">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h5 class="hando">Handover</h5>
                                <label class="radio-inline">
                                    <input type="radio" id="check_vendor" name="vendor" value="Vendor"> Vendor
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="check_emp" name="vendor" value="Employee"> Employee
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="check_others" name="vendor" value="Others"> Others
                                </label>
                            </div>

                            <div class="col-sm-4" id="vendor">
                                <div class="form-group">
                                    <label>Vendor Name *</label>
                                    <select class="form-control" id="ven_name" name="ven_name">
                                        <option value="">Select</option>
                                        <option value="Shailers Solution Pvt Ltd">Shailers Solution Pvt Ltd</option>
                                        <option value="EI Network Pvt Ltd">EI Network Pvt Ltd</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4" id="employee">
                                <div class="form-group">
                                    <label>Employee *</label>
                                    <select class="form-control" id="employee" name="employee">
                                        <option value="">Select</option>
                                        <option value="Ashutosh Pathak">Ashutosh Pathak</option>
                                        <option value="Dipanshu Roy">Dipanshu Roy</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4" id="others">
                                <div class="form-group">
                                    <label>Others *</label>
                                    <input type="text" name="other" class="form-control" id="other" placeholder="Enter Name">
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

        <div class="row mt-4">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Outward Assests List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Assets Type</th>
                                    <th>Brand Name</th>
                                    <th>Modal</th>
                                    <th>Handover Person</th>
                                    <th>Person Name</th>
                                    <th>Date</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>Camera</td>
                                    <td>Zebronic Web Cam 04</td>
                                    <td>Hp</td>
                                    <td>Vendor</td>
                                    <td>Shailers Solution Pvt Ltd</td>
                                    <td>2022-01-10 08:11:06</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>RAM Upgrade</td>
                                    <td>16gb DDR3</td>
                                    <td>Mac</td>
                                    <td>Vendor</td>
                                    <td>EI Network Pvt Ltd</td>
                                    <td>2022-01-10 08:11:06</td>
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <script type="text/javascript">
        $('#vendor').hide();
        $('#employee').hide();
        $('#others').hide();
        $(document).ready(function(){
            $("#check_vendor").click(function(){
                $('#vendor').show();
                $('#employee').hide();
                $('#others').hide();
                });
                $("#check_emp").click(function(){
                    $('#vendor').hide();
                    $('#employee').show();
                $('#others').hide();
            });
            $("#check_others").click(function(){
                $('#vendor').hide();
                $('#employee').hide();
                $('#others').show();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>
@endsection('content')