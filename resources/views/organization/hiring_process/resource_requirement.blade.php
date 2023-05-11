@extends('layouts.organization.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Create Resource Requirement</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('create-resource-requirement')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id(this.value);">
                                        @if(!empty($office))
                                            <option value="">Select Office</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <select class="form-control" id="department_id" name="department_id" required onchange="get_designation(this.value);">
                                        @if(!empty($department))
                                            <option value="">Select Department</option>
                                            @foreach($department as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Position Name</label>
                                    <select class="form-control" id="position_id" name="position_id" required>
                                        @if(!empty($position))
                                            <option value="">Select Position</option>
                                            @foreach($position as $row2)
                                                <option value="{{$row2->id}}" data-id="{{$row2->id}}" @if(!empty($update->position_id)) @if($update->position_id==$row2->id) selected @endif @endif>{{$row2->position_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Job Title</label>
                                    <input type="text" class="form-control" id="job_title" name="job_title" value="@if(!empty($update->job_title)){{$update->job_title}}@endif" placeholder="Enter Job Title" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Number of Vacancy</label>
                                    <input type="number" class="form-control" id="no_of_vacancy" name="no_of_vacancy" value="@if(!empty($update->no_of_vacancy)){{$update->no_of_vacancy}}@endif" placeholder="Enter Number of Vacancy" minlength="1" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Minimum Salary</label>
                                    <input type="number" class="form-control" id="minimum_salary" name="minimum_salary" value="@if(!empty($update->minimum_salary)){{$update->minimum_salary}}@endif" placeholder="Enter Minimum Salary" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Maximum Salary</label>
                                    <input type="number" class="form-control" id="maximum_salary" name="maximum_salary" value="@if(!empty($update->maximum_salary)){{$update->maximum_salary}}@endif" placeholder="Enter Maximum Salary" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Job Type</label>
                                    <select name="job_type" class="form-control">
                                        <option value="">--select--</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Job Description</label>
                                    <textarea type="hiden" name="description" value="@if(!empty($update->description)){{$update->description}}@endif" required></textarea>
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
    function get_office_id() {
        var department_id = $('#office_id option:selected').data('id');
        $('#department_id').empty();
        $('#designation_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-department-name')}}",
            data: {
                department_id: department_id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#department_id').append('<option value="">Select Department</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
            }
        });
    }
    function get_designation() {
        var office_id = $('#office_id option:selected').data('id');
        var department_id = $('#department_id option:selected').data('id');
        $('#position_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-designation')}}",
            data: {
                office_id: office_id,
                department_id: department_id,
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#position_id').append('<option value="">Select Designation</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                }
            }
        });
    }
</script>
<script>
    CKEDITOR.replace( 'description' );
</script>
@endsection('content')