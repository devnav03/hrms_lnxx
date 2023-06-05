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
                                <h5 class="" id="">Salary History Of {{ date('M, Y', strtotime($id)) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">S.No</th>
                                    <th>Emp Code</th>
                                    <th>Emp Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th style="text-align: center;">Present</th>
                                    <th style="text-align: center;">Absent</th>
                                    <th style="text-align: center;">Leave</th>
                                    <th style="text-align: center;">Download</th>
                                </tr>
                            </thead>
                            <tbody id="">
                            @if($data)
                                @php
                                $i = 0;
                                @endphp
                            @foreach($data as $row)
                                @php
                                $i++;
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $i }}</td>
                                    <td>{{ $row->employee_code }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->department_name }}</td>
                                    <td>{{ $row->position_name }}</td>
                                    <td style="text-align: center;">{{ $row->present }}</td>
                                    <td style="text-align: center;">{{ $row->absent }}</td>
                                    <td style="text-align: center;">{{ $row->leave }}</td>
                                    <td style="text-align: center;"><a class="btn btn-primary btn-xs" href="{{ route('export-salary-slip', $row->id) }}"><i class="fa fa-download" style=""></i></a></td>
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

</style>

    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>

@endsection('content')