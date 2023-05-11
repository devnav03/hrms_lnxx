@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="">Employee Salary View</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Emp Code</th>
                                    <th>Emp Name</th>
                                    <th>Salary</th>
                                    <th>Leave</th>
                                    <th>Working Days</th>
                                    <th>Total Days</th>
                                    <th>Earning</th>
                                    <th>Deduction</th>
                                    <th>Net Salary</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>LNXX0079</td>
                                    <td>Ashutosh Pathak</td>
                                    <td>10000</td>
                                    <td>12</td>
                                    <td></td>
                                    <td>31</td>
                                    <td>10000</td>
                                    <td>4000</td>
                                    <td>188</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>LNXX0080</td>
                                    <td>Dipanshu Roy</td>
                                    <td>20000</td>
                                    <td>12</td>
                                    <td></td>
                                    <td>31</td>
                                    <td>20000</td>
                                    <td>8000</td>
                                    <td>275</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-2 text-right mt-3">
                <a href="#" class="btn btn-primary btn-sm">Save & Generate Slip</a>
            </div>
            <div class="col-md-2 text-right mt-3">
                <a href="{{url('salary-generation')}}" class="btn btn-primary btn-sm">Go Back & Modify</a>
            </div>
        </div>

    </div>
    
    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>

@endsection('content')