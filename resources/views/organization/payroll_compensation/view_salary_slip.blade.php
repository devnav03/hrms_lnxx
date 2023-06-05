@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <form method="post" action="{{ route('employee-salary-slip') }}">
            {{ csrf_field() }} 
        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="">View Salary <span style="float: right;">{{ date('M, Y', strtotime($month_year)) }}</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                            <input type="hidden" name="month_year" value="{{ $month_year }}">
                        <table id="examples_123" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">S.No</th>
                                    <th>Emp Code</th>
                                    <th>Emp Name</th>
                                    <th style="text-align: center;">Salary</th>
                                    <th style="text-align: center;">Present</th>
                                    <th style="text-align: center;">Absent</th>
                                    <th style="text-align: center;">Leave</th>
                                    <th style="text-align: center;">Working Days</th>
                                    <th style="text-align: center;">Total Days</th>
                                    <th style="text-align: center;">Earning</th>
                                    <th style="text-align: center;">Leave Deduction</th>
                                    <th style="text-align: center;">Other Deduction</th>
                                    <th style="text-align: center;">Incentive/Bonus</th>
                                    <th style="text-align: center;">Net Salary</th>
                                </tr>
                            </thead>
                            <tbody id="">
                            @if($request)
                                @php
                                $i = 0;
                                @endphp
                            @foreach($request->user_id as $key => $userId)
                                @php
                                $i++;
                                $emp_info = get_user_info($userId);
                                @endphp
                                <tr>
                                    <input type="hidden" name="user_id[]" value="{{ $userId }}">
                                    <td style="text-align: center;">{{ $i }}</td>
                                    <td>{{ $emp_info['employee_code'] }}</td>
                                    <td>{{ $emp_info['name'] }}</td>
                                    <td style="text-align: center;">{{ $request->net_salary[$userId] }}</td>

                                    <td style="text-align: center;">{{ $request->present[$userId] }}</td>
                                    <td style="text-align: center;">{{ $request->absent[$userId] }}</td>
                                    <td style="text-align: center;">{{ $request->leave[$userId] }}</td>
                                    <td style="text-align: center;">{{ $request->working_day[$userId] }}</td>
                                    <td style="text-align: center;">{{ $request->total_days[$userId] }}</td>
                                    <td style="text-align: center;">{{ round($request->only_earn_salary[$userId], 2) }}</td>
                                    <td style="text-align: center;">{{ round($request->all_deduction[$userId], 2) }}</td>
                                    <td style="text-align: center;">{{ $request->deduction_other[$userId] }}</td>
                                    <td style="text-align: center;">{{ $request->bonus_incentive[$userId] }}</td>
                                    <td style="text-align: center;">{{ round($request->net_earn_salary[$userId] - $request->deduction_other[$userId]) }}</td>
                                </tr>
                                @endforeach 
                            @endif 
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-2 text-right mt-3">
                <input type="submit" value="Save & Generate Slip" class="btn btn-primary btn-sm">
            </div>
            <div class="col-md-2 text-right mt-3">
                <a href="{{url('salary-generation')}}" class="btn btn-primary btn-sm">Go Back & Modify</a>
            </div>
        </div>
        </form> 


    </div>
    

<style type="text/css">
#examples_123 th {
    border: 1px solid #ddd;
    font-weight: normal;
    padding: 8px;
    height: 50px;
    background: #f3f3f3;
}  
#examples_123 td{
    border: 1px solid #ddd;
    padding: 8px;
}
#examples_123 tr:hover {
    background: #e2fab5;
}
</style>

    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>

@endsection('content')