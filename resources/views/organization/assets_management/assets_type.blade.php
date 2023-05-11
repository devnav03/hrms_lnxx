@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Asset Type</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('assets-type')}}" method="POST">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required>
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->address}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Assets Type *</label>
                                    <input type="text" name="assets_type" id="assets_type" class="form-control" placeholder="Enter Assets Type" required>
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
                        <h5 class="">Assets Item List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Assets Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>Camera</td>
                                    <td><a class="btn btn-success btn-sm">Active</td>
                                    <td>
                                        <a href="#" class="text-primary mx-2"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>RAM Upgrade</td>
                                    <td><a class="btn btn-danger btn-sm">Deactive</td>
                                    <td>
                                        <a href="#" class="text-primary mx-2"><i class="fa fa-pencil"></i></a>
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