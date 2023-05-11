@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Salary Generation</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('salary-generation')}}" method="POST">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id();">
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department *</label>
                                    <select class="form-control" id="department_id" name="department_id" required onchange="get_designation();">
                                        @if(!empty($department))
                                            <option value="">--Select--</option>
                                            @foreach($department as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Year *</label>
                                    <select class="form-control" id="year" name="year" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Month *</label>
                                    <select class="form-control" id="month" name="month" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Proceed</button>
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
                                <h5 class="" id="">Generate Salary List</h5>
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
                                    <th>Designation</th>
                                    <th>Salary</th>
                                    <th>Attendance</th>
                                    <th>Absent</th>
                                    <th>No. of Leave</th>
                                    <th>Incentive</th>
                                    <th>Bonus</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>LNXX0079</td>
                                    <td>Ashutosh Pathak</td>
                                    <td>Web Developer</td>
                                    <td>10000</td>
                                    <td></td>
                                    <td>No</td>
                                    <td>12</td>
                                    <td><input type="text" name="incentive" class="form-control" style="height:40%;width:70%"></td>
                                    <td><input type="text" name="bonus" class="form-control" style="height:40%;width:70%"></td>
                                </tr>
                                
                                <tr>
                                    <td>1</td>
                                    <td>LNXX0080</td>
                                    <td>Dipanshu Roy</td>
                                    <td>Web Developer</td>
                                    <td>20000</td>
                                    <td></td>
                                    <td>No</td>
                                    <td>12</td>
                                    <td><input type="text" name="incentive" class="form-control" style="height:40%;width:70%"></td>
                                    <td><input type="text" name="bonus" class="form-control" style="height:40%;width:70%"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-right mt-3">
                <a href="{{url('view-salary-slip')}}" class="btn btn-primary btn-sm">Proceed to Generate</a>
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
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>
@endsection('content')