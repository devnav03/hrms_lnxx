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
    #hidden_div{
        display: none;
    }
    .btn-app{
        padding: 0.5rem 0.81rem;
        background-color: #ffbf36;
        border-radius: 3px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Update Assets Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table tbl-border">
                            <a href="{{url('assets-report')}}" class="btn btn-info btn-sm mb-2"><i class="fa fa-arrow-left"></i> Go Back</a>
                            <thead>
                                <tr>
                                    <th>Emp Code</th>
                                    <th>Emp Name</th>
                                    <th>From Date</th>
                                    <th>End Date</th>
                                    <th>Assets Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody class="update_data">
                                <tr>
                                    <td>LNXX0079</td>
                                    <td>Ashutosh Pathak</td>
                                    <td>12/01/2022</td>
                                    <td>01/31/2023</td>
                                    <td>Camera</td>
                                    <td><span class="btn-app">Pending</span></td>
                                    <td>For Laptop</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr style="margin: 30px -30px 25px;">
                        <div class="col-md-12">
                            <h4 class="text-center mb-4" style="color:#135cbb">Employee :- Ashutosh Pathak  (LNXX0079) (L4) Assign Assets</h4>
                        </div>
                        
                            <form action="{{url('return-assets-report-status')}}" method="POST">
                                <table class="table tbl-border">
                                    <thead>
                                        <tr>
                                            <th>Admin Description</th>
                                            <th>Assets Type</th>
                                            <th>Asset Model</th>
                                            <th>Asset Serial Number</th>
                                            <th>Asset Start Date</th>
                                            <th>Asset End Date</th>
                                        </tr>
                                        <tr></tr>
                                    </thead>
                                    <tbody class="update_data">
                                        <tr>
                                            <td>For Laptop</td>
                                            <td>Camera</td>
                                            <td>Zebronic Web Cam 03</td>
                                            <td>003</td>
                                            <td>12/01/2022</td>
                                            <td>01/31/2023</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row mt-3">
                                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                        <label>Return Update Status</label>
                                        <select name="chng_status" id="chng_status" class="form-control">
                                            <option value="">Change Status</option>
                                            <option value="Return">Return</option>
                                            <option value="Approve">Approve</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <label>Return Assets Description By Admin</label>
                                        <textarea rows="2" name="admin_description" class="form-control" required=""></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-sm mr-2">Update</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
@endsection('content')