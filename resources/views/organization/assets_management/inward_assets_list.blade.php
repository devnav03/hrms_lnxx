@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('assets-inward-outward')}}" class="btn btn-success btn-sm">Add New <i class="fa fa-plus"></i></a>
            </div>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Inward Assets List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Vendor Name</th>
                                    <th>Assets Type</th>
                                    <th>Brand Name</th>
                                    <th>Challan Document</th>
                                    <th>Invoice Document</th>
                                    <th>Challan No.</th>
                                    <th>Receiver Name</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>Shailers Solution Pvt Ltd</td>
                                    <td>Camera</td>
                                    <td>Zebronic Web Cam 04</td>
                                    <td></td>
                                    <td></td>
                                    <td>0618</td>
                                    <td>Ashutosh Pathak</td>
                                    <td>2022-01-10</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>EI Network Pvt Ltd</td>
                                    <td>RAM Upgrade</td>
                                    <td>16gb DDR3</td>
                                    <td></td>
                                    <td></td>
                                    <td>805</td>
                                    <td>Dipanshu Roy</td>
                                    <td>2022-01-14</td>
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
@endsection('content')