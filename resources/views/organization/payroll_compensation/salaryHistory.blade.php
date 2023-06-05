@extends('layouts.organization.app')
@section('content')

<style>
    .lable-danger{
        background-color: #d9534f;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    .lable-success{
        background-color: #5cb85c;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    #leave_data td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    @media (min-width: 992px){
        .modal-lg, .modal-xl {
            max-width: 1000px;
        }
    }
    .dropdown .dropdown-menu{
        box-shadow: 0px 1px 15px 1px rgb(0 0 0 / 35%);
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
         <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Salary History</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="max-width: 40px; text-align: center;">Sr No.</th>
                                    <th>Month</th> 
                                    <th style="max-width: 120px;text-align: center;">No Of Employees</th> 
                                    <th style="max-width: 45px;">View</th> 
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($data))
                                @php
                                    $i = 0;
                                @endphp
                                @foreach($data as $row)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td style="max-width: 40px; text-align: center;">{{ $i }}</td>
                                    <td>{{ date('M-Y', strtotime($row->month_year)) }}</td>
                                    <td style="text-align: center;">{{ $row->total }}</td>
                                    <td><a class="btn btn-primary btn-xs" href="{{ route('salary-history-by-month', $row->month_year) }}"><i class="fa fa-eye" style=""></i></a></td> 
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



<script>
 
    var loadFile = function(event) {
        document.getElementById('output').setAttribute("style",
            "width: 8rem;height: 8rem;border-radius: 0.25rem;object-fit: contain;max-height: 51px;max-width: 10rem;margin-top: -9px;"
            );
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
</script>
<script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>
@endsection('content')