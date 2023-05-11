@extends('layouts.organization.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Employee</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row" action="{{url('add-emp-assign-project')}}">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Project Name</label>
                                    <select class="form-control" id="project_id" name="project_id" required>
                                        <option value="">Select</option>
                                            @if(!empty($project_details))
                                                @foreach($project_details as $row)
                                                    <option value="{{$row->id}}">{{$row->project_name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control" id="employee_id" name="employee_id" required>
                                        <option value="">Select</option>
                                            @if(!empty($emp_details))
                                                @foreach($emp_details as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea type="hiden" name="description" required></textarea>
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
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>
@endsection('content')