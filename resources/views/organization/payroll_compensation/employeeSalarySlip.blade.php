@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper" style="max-width: 940px; margin: 0 auto; background: transparent;"> 

        @foreach($data as $dt)                                
        <table class="table table-stripped table-bordered">
            <tbody>
                                                                                                        <tr>
            <td colspan="3" style="border-right: 1px solid #fff;">
                <h4>{{ $organisation->company_name }}</h4>
                <p><b>Registered Office:-</b></p>
                <p>{{ $organisation->address }}</p>                                                                                                    
                <p><b>Mobile:</b> {{ $organisation->mobile }}</p>                                                            
            </td>
            <td colspan="2" class="text-center">
                <img src="images/jcbbl_logo.jpg" style="width:80px"><br>
              <!--   <p>www.abc.com</p>
                <p>(ISO 270001:2013 Certified)</p> -->
            </td>
        </tr>                                                   
        </tbody>
    </table>
       <h4 style="text-align: center; width: 100%; margin-top: 20px;"><b><p style="font-size: 17px;text-align: center;">SALARY SLIP OF {{ date('M-Y', strtotime($month_year)) }} </p></b></h4>
    <table class="table table-stripped table-bordered" style="margin-top: 12px;width: 90%;margin-left: 31px;"> 
            <tr>
                <th style="font-size: 12px;font-family: sans-serif; padding: 7px;"><b>Employee Name</b></th>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $dt->name }}</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">Date Of Joining</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ date('d-m-Y', strtotime($dt->created_at)) }}</td>
            </tr>
            <tr>
                <th style="font-size: 12px;font-family: sans-serif; padding: 7px;"><b>Designation</b></th>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $dt->position_name }}</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td> 
            </tr>
            <tr>
                <th style="font-size: 12px;font-family: sans-serif; padding: 7px;"><b>Employee Id</b></th>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $dt->employee_code }}</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td> 
            </tr>
            <tr>
                <th  style="font-size: 12px;font-family: sans-serif; padding: 7px;"><b>Email</b></th>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $dt->email }}</td>
                 

            </tr>
    </table>
     <table style="margin-top: 12px;width: 90%;margin-left: 31px;"> 
        
        <tr>
            <td style="width: 50%;vertical-align: top;">
            <table class="table table-stripped table-bordered"> 
                    <thead>
                        <th colspan="2" style="font-size: 12px;padding: 7px;text-align: center;"><b>+ Earning</b></th>                                                      
                    </thead>
                    <tbody>
@php
$SalaryMaster = DB::table('salary_masters')->where('office_id', $dt->office_id)->where('earning_deduction', 1)->select('amount_percent', 'header_name')->get();
$extra_earn = 0;
@endphp
                    @foreach($SalaryMaster as $SalaryM)
                        <tr>
                            <td style="font-size: 12px;padding: 7px;">{{ $SalaryM->header_name }}</td>
@php
$other_earn =  $dt->net_salary * ($SalaryM->amount_percent / 100);
@endphp
                            <td style="font-size: 12px;padding: 7px;">{{ round($other_earn) }}</td>
                        </tr>                  
                    @endforeach
                    @if($dt->incentive)
                        <tr>
                            <td style="font-size: 12px;padding: 7px;">Incentive</td>
                            <td style="font-size: 12px;padding: 7px;">{{ $dt->incentive }}</td>
                        </tr>
                        @php
                            $extra_earn += $dt->incentive;
                        @endphp
                    @endif
                    @if($dt->bonus)
                        <tr>
                            <td style="font-size: 12px;padding: 7px;">Bonus</td>
                            <td style="font-size: 12px;padding: 7px;">{{ $dt->bonus }}</td>
                        </tr>
                        @php
                            $extra_earn += $dt->bonus;
                        @endphp
                    @endif

                    </tbody>                                                                                                                                               
                </table>
                </td>
                <td style="width: 50%;vertical-align: top;">
                <table class="table table-stripped table-bordered"> 
                    <thead>                                                     
                        <th colspan="2" style="font-size: 12px;padding: 7px;text-align: center;">
                            <b>- Deduction</b>
                        </th> 
                    </thead>
                    <tbody>
@php
$SalaryMaster = DB::table('salary_masters')->where('office_id', $dt->office_id)->where('earning_deduction', 2)->select('amount_percent', 'header_name')->get();
$extra_deduct = 0;
$abs_salary = round($dt->deduction_salary);
$extra_deduct += $abs_salary;
@endphp
                @if($SalaryMaster)
                    @foreach($SalaryMaster as $SalaryM)

@php
$other_deduct =  $dt->net_salary * ($SalaryM->amount_percent / 100);
$extra_deduct += $other_deduct;
@endphp
                    <tr>
                        <td style="font-size: 12px;padding: 7px;">{{ $SalaryM->header_name }}</td>
                        <td style="font-size: 12px;padding: 7px;">{{ $other_deduct }}</td>
                    </tr>
                    @endforeach
                @endif
                    <tr>
                        <td style="font-size: 12px;padding: 7px;">Leave Without Pay ({{ $dt->absent - $dt->leave }})</td>
                        <td style="font-size: 12px;padding: 7px;">{{ $abs_salary }}</td> 
                    </tr> 

                    </tbody>                                                    
                </table> 
                </td>  
                </tr>
            </table>

            <div class="col-md-12">                                             
            <table class="table table-stripped table-bordered" style="margin-top:0px; width: 93%;margin-left: 14px;">
            <tr style="background-color: #ffb37c;">
                <td style="font-size: 12px;padding: 7px;text-align: end;width: 40%;">
                    <b>Total Earning:-</b>
                </td>
                <td style="font-size: 12px;padding: 7px;text-align: left;width: 10%;"><b>{{ $dt->net_salary + $extra_earn }}/-</b></td>
                <td style="font-size: 12px;padding: 7px;text-align: end;"><b>Total Deduction:-</b></td>
                <td style="font-size: 12px;padding: 7px;text-align: left;width: 9%;width: 17%;"><b>{{ $extra_deduct }}/-</b></td>
            </tr>
            <tr style="background-color: #74a574b3;">
                <td colspan="3" style="font-size: 12px;padding: 7px;text-align: end;;"><p style="float: left;margin-top: 0px;"> (Earning – Deductions + Round Off)</p><b>Net Pay:-</b></td>
                <td style="font-size: 12px;padding: 7px;text-align: left;"><b>{{ round($dt->net_salary + $extra_earn - $extra_deduct) }}/-</b></td>
            </tr> 
            </table>                                            
            </div>
            <div class="col-md-12">
                <img src="images/human_ew.jpg" style="width: 150px; float: right;margin-right: 42px;">
            </div>
            <div class="col-md-12" style="text-align: center;font-size: 12px;margin: 170px auto 60px auto;">This is computer generated slip hence not need any signature</div>

        @endforeach
                            
    </div>
    </div>

    
<style type="text/css">
.table-bordered {
    border: 1px solid #ddd;
}
.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, 
.table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, 
.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border: 1px solid #ddd;
}
p {
    margin-bottom: 0 !important;
    font-size: 11px;
}
h4 {
    margin: 0 0 0 0;
}
.h4, h4 {
    font-size: 18px;
}
b, strong {
    color: #333;
    font-weight: 700;
}




</style>

@endsection('content')