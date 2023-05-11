@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Brand</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('assets-brand')}}" method="POST">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Assets Name *</label>
                                    <select class="form-control" name="assets_name">
                                        <option value="">Select</option>
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Assests Brand Name *</label>
                                    <input type="text" name="assests_brand_name" id="assests_brand_name" class="form-control" placeholder="Enter Assests Brand Name" required>
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
                        <h5 class="">Assets Brand List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Assets Name</th>
                                    <th>Assets Brand</th>
                                    <th>Action</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>Laptop Adapter</td>
                                    <td>Mac</td>
                                    <td>
                                        <a href="{{url('edit')}}" class="text-primary mx-2"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>CPU</td>
                                    <td>Hp</td>
                                    <td>
                                        <a href="{{url('edit')}}" class="text-primary mx-2"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
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

@endsection('content')