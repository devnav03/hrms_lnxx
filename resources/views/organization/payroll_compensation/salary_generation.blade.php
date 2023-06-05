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
        
        <form method="post" action="{{ route('view-salary-slip') }}">
        {{ csrf_field() }}    
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
                        <table id="examples_salary" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">S.No</th>
                                    <th>Emp Code</th>
                                    <th>Emp Name</th>
                                    <th>Designation</th>
                                    <th>Salary</th>
                                    <th style="text-align: center;">Attendance</th>
                                    <th style="text-align: center;">Absent</th>
                                    <th style="text-align: center;">No. of Leave</th>
                                    <th style="text-align: center;">Incentive</th>
                                    <th style="text-align: center;">Bonus</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @php
                                $i = 0;
                                $count = 0;
                                if(isset($month_filter)) {
                                $old_date = $year_filter.'-'.$month_filter;
                                @endphp
                                <input type="hidden" name="month_year" value="{{ $old_date }}">
                                @php
                                } else {
                                $old_date = date('Y-m');
                                @endphp
                                <input type="hidden" name="month_year" value="{{ $old_date }}">
                                @php
                                }
                                @endphp

                                @foreach($users as $user)
                                @php
                                    $i++;
                                if(isset($month_filter)) {
                                $get_attendance = get_attendance($month_filter, $year_filter, $user->id);
                                
                                } else {
                                $month = date('m');
                                $year = date('Y');         
                                $get_attendance = get_attendance($month, $year, $user->id);
                                }
                                @endphp

                                <tr @if($get_attendance['sal_gen'] != 0) style="background: #e2fab5;" @endif >

                                    <td style="text-align: center;">{{ $i }}</td>
                                    <td>{{ $user->employee_code }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->position_name }}</td>
                                    <td style="text-align: center;">{{ $user->salary }} 
                                @if($get_attendance['sal_gen'] == 0) 
                                    <input type="hidden" name="earn_salary[{{$user->id}}]" value="{{ $get_attendance['salary'] }}">
                                    <input type="hidden" name="user_id[]" value="{{ $user->id }}">
                                    <input type="hidden" name="leave[{{$user->id}}]" value="{{ $get_attendance['leave_day'] }}">
                                    <input type="hidden" name="absent[{{$user->id}}]" value="{{ $get_attendance['abs'] }}">
                                    <input type="hidden" name="abs_deduction[{{$user->id}}]" value="{{ $get_attendance['abs_deduction'] }}">
                                    <input type="hidden" name="net_salary[{{$user->id}}]" value="{{ $user->salary }}">
                                    <input type="hidden" name="present[{{$user->id}}]" value="{{ $get_attendance['present'] }}">

                                    <input type="hidden" name="working_day[{{$user->id}}]" value="{{ $get_attendance['working_day'] }}">
                                    <input type="hidden" name="total_days[{{$user->id}}]" value="{{ $get_attendance['total_days'] }}">
                                    @php
                                        $count++;
                                    @endphp
                                @endif         
                                    </td>
                                    <td style="text-align: center;">{{ $get_attendance['present'] }} </td>
                                    <td style="text-align: center;">{{ $get_attendance['abs'] }} </td>
                                    <td style="text-align: center;"> {{ $get_attendance['leave_day'] }} </td>
                                    <td style="text-align: center;"><input type="text" name="incentive[{{ $user->id }}]" class="form-control" style="max-width: 90px;margin: 0 auto;"></td>
                                    <td style="text-align: center;"><input type="text" name="bonus[{{ $user->id }}]" class="form-control" style="max-width: 90px;margin: 0 auto;"></td>
                                </tr>
                           
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-right mt-3">
               <!--  <a href="{{url('view-salary-slip')}}" class="btn btn-primary btn-sm">Proceed to Generate</a> -->
                @if($count != 0)
                <input type="submit" class="btn btn-primary btn-sm" value="Proceed to Generate">
                @endif
            </div>
        </div>
        </form> 


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

<style type="text/css">
#examples_salary th {
    border: 1px solid #ddd;
    font-weight: normal;
    padding: 8px;
    height: 50px;
    background: #f3f3f3;
}  
#examples_salary td{
    border: 1px solid #ddd;
    padding: 8px;
}

</style>


@endsection('content')