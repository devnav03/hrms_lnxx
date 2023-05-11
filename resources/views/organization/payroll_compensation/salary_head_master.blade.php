@extends('layouts.organization.app')
@section('content')
<style>
    .dataTables_length{
        display: none !important;
    }
    .dataTables_filter{
        display: none !important;
    }
    .dataTables_info{
        display: none !important;
    }
    .dataTables_paginate {
        display: none !important;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Salary Header Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('salary-head-master')}}" method="POST">
                            @csrf
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required>
                                        @if(!empty($office))
                                            <option value>--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Header Type *</label>
                                    <select class="form-control" id="earning_deduction" name="earning_deduction" required>
                                        <option value="1" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>Earning</option>
                                        <option value="2" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>Deduction</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Header Name *</label>
                                    <input type="text" class="form-control" id="header_name" name="header_name" placeholder="Enter Head Name" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Percentage *</label>
                                    <input type="number" min="1" class="form-control" id="percentage" name="percentage" placeholder="Enter Percentage" required>
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
        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="">Search Salary Header List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <form action="{{url('salary-head-master')}}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" id="office_id" name="office_id">
                                        @if(!empty($office))
                                            <option value="">--Select Office--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-top: -0.1rem;">Search</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-6 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <h5 class="">Earning</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="examples display table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Office Name</th>
                                    <th>Earning Heads</th>
                                    <th>Amount (%)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($earning))
                                @foreach($earning as $rows)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$rows->office_name}}</td>
                                    <td>{{$rows->header_name}}</td>
                                    <td>{{$rows->amount_percent}}%</td>
                                    <td><a href="{{url('salary-head-master',$rows->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a><a href="{{url('delete-salary-head-master',$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <h5 class="">Deduction</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="examples display table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Office Name</th>
                                    <th>Earning Heads</th>
                                    <th>Amount (%)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($deduction))
                                @foreach($deduction as $rows)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$rows->office_name}}</td>
                                    <td>{{$rows->header_name}}</td>
                                    <td>{{$rows->amount_percent}}%</td>
                                    <td><a href="{{url('salary-head-master',$rows->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a><a href="{{url('delete-salary-head-master',$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script>
        $(document).ready(function () {
            var datatable = $('.examples').dataTable();
        });
    </script>

@endsection('content')