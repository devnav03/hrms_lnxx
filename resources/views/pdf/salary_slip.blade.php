<table class="table table-stripped table-bordered" style="width: 100%;height: 100px;">
            <tbody>
            <tr>
            <td colspan="3" style="border-right: 1px solid #fff;">
                <h4 style="margin-top: 0px;margin-bottom: 0px;">{{ $company_name }}</h4>
                <p style="margin-top: 5px;"><b>Registered Office:-</b></p>
                <p style="margin-top: 5px;">{{ $address }}</p>
                <p style="margin-top: 5px;"><b>Mobile:</b> {{ $mobile }}</p>                                                          
            </td>
            <td colspan="2" class="text-right" style="border-left: 1px solid #fff;vertical-align: middle;">
                <img src="images/jcbbl_logo.jpg" style="width:120px; float: right;"><!-- <br> -->
                <!-- <p>www.abc.com</p>
                <p>(ISO 270001:2013 Certified)</p> -->
            </td>
        </tr>                                                   
        </tbody>
    </table>
       <h4 style="text-align: center; width: 100%; margin-top: 20px;"><b><p style="font-size: 17px;text-align: center; text-transform: uppercase; ">SALARY SLIP OF {{ date('F-Y', strtotime($month_year)) }} </p></b></h4>
    <table class="table table-stripped table-bordered" style="margin-top: 12px;width: 90%;margin-left: 31px;"> 
            <tr>
                <td style="font-size: 12px;text-align: left; padding: 7px;">Employee Name</td>
                <td style="font-size: 12px;padding: 7px;">{{ $name }}</td>
                <td style="font-size: 12px;text-align: left; padding: 7px;">Date Of Joining</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ date('d-m-Y', strtotime($created_at)) }}</td>
            </tr>
            <tr>
                <td style="font-size: 12px;text-align: left; padding: 7px;">Designation</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $position_name }}</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td> 
            </tr>
            <tr>
                <td style="font-size: 12px;text-align: left; padding: 7px;">Employee Id</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $employee_code }}</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td> 
            </tr>
            <tr>
                <td  style="font-size: 12px;text-align: left;padding: 7px;">Email</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;">{{ $email }}</td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td>
                <td style="font-size: 12px;font-family: sans-serif; padding: 7px;"></td> 
                 

            </tr>
    </table>
     <table style="margin-top: 12px;width: 90%;margin-left: 31px;"> 
        
        <tr>
            <td style="width: 50%;vertical-align: top;">
            <table class="table table-stripped table-bordered" style="width: 100%;"> 
                <thead>
                    <th colspan="2" style="font-size: 12px;padding: 7px;text-align: center;border: 1px solid #ddd;"><b>+ Earning</b></th>                                                      
                </thead>
                <tbody>
@php
$SalaryMaster = DB::table('salary_masters')->where('office_id', $office_id)->where('earning_deduction', 1)->select('amount_percent', 'header_name')->get();
$extra_earn = 0;
@endphp
                    @foreach($SalaryMaster as $SalaryM)
                        <tr>
                            <td style="font-size: 12px;padding: 7px;">{{ $SalaryM->header_name }}</td>
@php
$other_earn =  $net_salary * ($SalaryM->amount_percent / 100);
@endphp
                            <td style="font-size: 12px;padding: 7px;">{{ round($other_earn) }}</td>
                        </tr>                  
                    @endforeach
                    @if($incentive)
                        <tr>
                            <td style="font-size: 12px;padding: 7px;">Incentive</td>
                            <td style="font-size: 12px;padding: 7px;">{{ $incentive }}</td>
                        </tr>
                        @php
                            $extra_earn += $incentive;
                        @endphp
                    @endif
                    @if($bonus)
                        <tr>
                            <td style="font-size: 12px;padding: 7px;">Bonus</td>
                            <td style="font-size: 12px;padding: 7px;">{{ $bonus }}</td>
                        </tr>
                        @php
                            $extra_earn += $bonus;
                        @endphp
                    @endif

                    </tbody>                                                                                                                                               
                </table>
                </td>
                <td style="width: 50%;vertical-align: top;">
                <table class="table table-stripped table-bordered" style="width: 100%;"> 
                    <thead>                                                     
                        <th colspan="2" style="font-size: 12px;padding: 7px;text-align: center;border: 1px solid #ddd;">
                            <b>- Deduction</b>
                        </th> 
                    </thead>
                    <tbody>
@php
$SalaryMaster = DB::table('salary_masters')->where('office_id', $office_id)->where('earning_deduction', 2)->select('amount_percent', 'header_name')->get();
$extra_deduct = 0;
$abs_salary = round($deduction_salary);
$extra_deduct += $abs_salary;
@endphp
                @if($SalaryMaster)
                    @foreach($SalaryMaster as $SalaryM)

@php
$other_deduct =  $net_salary * ($SalaryM->amount_percent / 100);
$extra_deduct += $other_deduct;
@endphp
                    <tr>
                        <td style="font-size: 12px;padding: 7px;">{{ $SalaryM->header_name }}</td>
                        <td style="font-size: 12px;padding: 7px;">{{ $other_deduct }}</td>
                    </tr>
                    @endforeach
                @endif
                    <tr>
                        <td style="font-size: 12px;padding: 7px;">Leave Without Pay ({{ $absent - $leave }})</td>
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
                <td style="font-size: 12px;padding: 7px;text-align: right;width: 40%;">
                    <b>Total Earning:-</b>
                </td>
                <td style="font-size: 12px;padding: 7px;text-align: left;width: 10%;"><b>{{ $net_salary + $extra_earn }}/-</b></td>
                <td style="font-size: 12px;padding: 7px;text-align: right;"><b>Total Deduction:-</b></td>
                <td style="font-size: 12px;padding: 7px;text-align: left;width: 9%;width: 17%;"><b>{{ $extra_deduct }}/-</b></td>
            </tr>
            <tr style="background-color: #74a574b3;">
                <td colspan="3" style="font-size: 12px;padding: 7px;text-align: right;"><p style="float: left;margin-top: 0px;"> (Earning – Deductions + Round Off)</p> <b>Net Pay:-</b> </td>
                <td style="font-size: 12px;padding: 7px;text-align: left;"><b>{{ round($net_salary + $extra_earn - $extra_deduct) }}/-</b></td>
            </tr> 
            </table>                                            
            </div>
            <div class="col-md-12">
                <img src="images/human_ew.jpg" style="width: 150px; float: right;margin-right: 42px;">
            </div>
            <div class="col-md-12" style="text-align: center;font-size: 12px;margin: 170px auto 60px auto;">This is computer generated slip hence not need any signature</div>



<style type="text/css">
    
.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border: 1px solid #ddd;
}
.table th, .table td {
    padding: 1.25rem 0.9375rem;
    vertical-align: top;
    border-top: 1px solid #f3f3f3;
}
.h4, h4 {
    font-size: 18px;
}
p {
    margin-bottom: 0 !important;
    font-size: 11px;
}
b, strong {
    color: #333;
    font-weight: 700;
}
table {
    border-collapse: collapse;
}
*{
    font-family: sans-serif;
}

</style>















