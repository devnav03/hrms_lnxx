<?php
namespace App\Http\Controllers\organization\payroll_compensation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Organisation;
use App\Models\SalaryMaster;
use App\Models\SalaryHeadMaster;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\PositionMaster;
use App\Models\SalaryGenerate;
use App\Models\EmployeeInfo;
use App\Models\HolidayCalendar;
use DB;
use PDF;

class PayrollCompensationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    
    public function salaryHeadMasterEdit($id = null){
        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->id])->first();
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id->id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $update = SalaryMaster::where('id',$id)->first();
        $office_update = OfficeMaster::where('id',$update->office_id)->get();
        $earning = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=1 ORDER BY a.id,a.header_name DESC");
        $deduction = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=2 ORDER BY a.id,a.header_name DESC");  
            return view('organization.payroll_compensation.salary_head_master',compact('organisation','office','office_update','earning','deduction', 'update')); 
    }

    public function holidayCalendarEdit($id = null){
        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->id])->first();

        $update = HolidayCalendar::where('id',$id)->first();
        $holidays = HolidayCalendar::where('created_by', $user_id->id)->select('id', 'name', 'year', 'day', 'month', 'status')->get();

        return view('organization.payroll_compensation.holidayCalendar',compact('organisation','holidays', 'update'));
    }

    public function salaryHeadMasterDel($id = null){
        \DB::table('salary_masters')->where('id', $id)->delete();
        return redirect('salary-head-master')->with('success','Deleted successfuly');
    }

    public function holidayCalendarDel($id = null){
        \DB::table('holiday_calendars')->where('id', $id)->delete();
        return redirect('holiday-calendar')->with('success','Deleted successfuly');
    }

    public function salaryHistory(){
        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->id])->first();
        
        $data = SalaryGenerate::where('status', 1)->select('month_year', DB::raw('count(*) as total'))->groupBy('month_year')->orderBy('month_year', 'desc')->get();

        return view('organization.payroll_compensation.salaryHistory',compact('organisation', 'data'));
    }

    public function salaryHistoryByMonth($id = null){
        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->id])->first();
        
        $data = \DB::table('salary_generates')
            ->join('employee_infos', 'salary_generates.user_id', '=', 'employee_infos.user_id')
            ->join('position_masters', 'employee_infos.position_id', '=', 'position_masters.id')
            ->join('department_masters', 'employee_infos.department_id', '=', 'department_masters.id')
            ->join('users', 'users.id', '=', 'salary_generates.user_id')
            ->select('salary_generates.present', 'salary_generates.absent', 'salary_generates.leave', 'salary_generates.user_id', 'employee_infos.employee_code', 'employee_infos.office_id', 'employee_infos.position_id', 'position_masters.position_name', 'users.name', 'department_masters.department_name', 'salary_generates.id')
            ->where('salary_generates.month_year', $id)
            ->where('salary_generates.status', 1)
            ->where('employee_infos.employee_code', '!=', NULL)
            ->get(); 

        return view('organization.payroll_compensation.salaryHistoryByMonth',compact('organisation', 'data', 'id'));
    }

    public function exportSalarySlip($id = null){

        $datas = SalaryGenerate::
             join('employee_infos', 'salary_generates.user_id', '=', 'employee_infos.user_id')
            ->join('position_masters', 'employee_infos.position_id', '=', 'position_masters.id')
            ->join('users', 'users.id', '=', 'salary_generates.user_id')
            ->select('salary_generates.net_salary', 'salary_generates.present', 'salary_generates.absent', 'salary_generates.leave', 'salary_generates.incentive', 'salary_generates.bonus', 'salary_generates.earned_salary', 'salary_generates.deduction_salary', 'salary_generates.other_deduction', 'salary_generates.user_id', 'employee_infos.employee_code', 'employee_infos.office_id', 'employee_infos.position_id', 'position_masters.position_name', 'users.name', 'users.email', 'users.mobile', 'users.created_at', 'salary_generates.month_year')
            ->where('salary_generates.id', $id)
            ->where('employee_infos.employee_code', '!=', NULL)
            ->first();


        if($datas){

            $user_id = Auth::user();
            $organisation = Organisation::where(['user_id'=>$user_id->id])->first();


            $data['net_salary'] = $datas->net_salary;
            $data['present'] = $datas->present;
            $data['absent'] = $datas->absent;
            $data['leave'] = $datas->leave;
            $data['incentive'] = $datas->incentive;
            $data['bonus'] = $datas->bonus;
            $data['earned_salary'] = $datas->earned_salary;
            $data['deduction_salary'] = $datas->deduction_salary;
            $data['other_deduction'] = $datas->other_deduction;
            $data['user_id'] = $datas->user_id;
            $data['employee_code'] = $datas->employee_code;
            $data['office_id'] = $datas->office_id;
            $data['position_id'] = $datas->position_id;
            $data['position_name'] = $datas->position_name;
            $data['name'] = $datas->name;
            $data['email'] = $datas->email;
            $data['mobile'] = $datas->mobile;
            $data['created_at'] = $datas->created_at;
            $data['month_year'] = $datas->month_year;

            $data['company_name'] = $organisation->company_name;
            $data['address'] = $organisation->address;
            $data['mobile'] = $organisation->mobile;
            $data['logo'] = $organisation->logo;

            $name = $datas->name.'-salary_slip_of-'.$datas->month_year;

            $pdf = \PDF::loadView('pdf.salary_slip', $data);
            return $pdf->download($name.'.pdf');
        } else {
            return back;
        }

    }

    public function holidayCalendar(Request $request){

        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->id])->first();
        $update = [];

        if(!empty($request->segment(2))){
            $update = HolidayCalendar::where('id',$request->segment(2))->first();
        }

        if(!empty($request->year) && empty($request->name)){

            $holidays = HolidayCalendar::where('created_by', $user_id->id)->where('year', $request->year)->select('id', 'name', 'year', 'day','month', 'status')->get();

            return view('organization.payroll_compensation.holidayCalendar',compact('organisation','update','holidays'));
        } else {
            $holidays = HolidayCalendar::where('created_by', $user_id->id)->select('id', 'name', 'year', 'day', 'month', 'status')->get();
        }

        if(!empty($request->year) && !empty($request->name)){
            if($request->upd_id != 0){

                    $select = HolidayCalendar::where('id', $request->upd_id)->first();
                    $select->created_by = $user_id->id;
                    $select->year = $request->year;
                    $select->name = $request->name;
                    $select->day = $request->day;
                    $select->month = $request->month;
                    $select->save();

                    return redirect('holiday-calendar')->with('success','Updated successfuly');

            } else {
                $error_up = HolidayCalendar::select('name')->where('year',$request->year)->where('name',$request->name)->first();
                if(!empty($error_up)){
                    return redirect('holiday-calendar')->with('error',$error_up->name.' Salary header already exist');
                }
                    $select = new HolidayCalendar();
                    $select->created_by = $user_id->id;
                    $select->year = $request->year;
                    $select->name = $request->name;
                    $select->day = $request->day;
                    $select->month = $request->month;
                    $select->save();
                    return redirect('holiday-calendar')->with('success','Saved successfuly');
            }
        }
        return view('organization.payroll_compensation.holidayCalendar',compact('organisation','update','holidays'));
    }


    public function salaryHeadMaster(Request $request){
        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->id])->first();

        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id->id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $office_update=[];
        if(!empty($request->segment(2))){
            $update = SalaryMaster::where('id',$request->segment(2))->first();
            $office_update = OfficeMaster::where('office_id',$update->office_id)->get();
        }
        if(!empty($request->office_id) && empty($request->header_name)){
            $earning = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=1 AND a.office_id=$request->office_id ORDER BY a.id,a.header_name DESC");
            $deduction = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=2 AND a.office_id=$request->office_id ORDER BY a.id,a.header_name DESC");
            return view('organization.payroll_compensation.salary_head_master',compact('organisation','office','office_update','earning','deduction'));
        } else {
            $earning = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=1 ORDER BY a.id,a.header_name DESC");
            $deduction = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=2 ORDER BY a.id,a.header_name DESC");
        }

        if(!empty($request->office_id) && !empty($request->header_name)){
            if($request->upd_id != 0){

                $sum = SalaryMaster::where('orgnization_id', $user_id->id)->where('office_id', $request->office_id)->where('id', '!=', $request->upd_id)->where('earning_deduction', $request->earning_deduction)->sum('amount_percent');

                $total_percent = $sum + $request->percentage;

                if($total_percent > 100){
                    return back()->with('max_hundred', 'max_hundred');
                } else {

                    $select = SalaryMaster::where('id', $request->upd_id)->first();
                    $select->orgnization_id = $user_id->id;
                    $select->office_id = $request->office_id;
                    $select->header_name = $request->header_name;
                    $select->earning_deduction = $request->earning_deduction;
                    $select->amount_percent = $request->percentage;
                    $select->save();

                    return redirect('salary-head-master')->with('success','Updated successfuly');

                }

            } else {
                $error_up = SalaryMaster::select('header_name')->where('office_id',$request->office_id)->where('header_name',$request->header_name)->where('earning_deduction',$request->earning_deduction)->first();
                if(!empty($error_up)){
                    return redirect('salary-head-master')->with('error',$error_up->header_name.' Salary header already exist');
                }

                $sum = SalaryMaster::where('orgnization_id', $user_id->id)->where('office_id', $request->office_id)->where('earning_deduction', $request->earning_deduction)->sum('amount_percent');
                $total_percent = $sum + $request->percentage;
                
                if($total_percent > 100){
                    return back()->with('max_hundred', 'max_hundred');
                } else {
                    $select = new SalaryMaster();
                    $select->orgnization_id = $user_id->id;
                    $select->office_id = $request->office_id;
                    $select->header_name = $request->header_name;
                    $select->earning_deduction = $request->earning_deduction;
                    $select->amount_percent = $request->percentage;
                    $select->save();
                    return redirect('salary-head-master')->with('success','Saved successfuly');
                }
            }
        }
        return view('organization.payroll_compensation.salary_head_master',compact('organisation','office','office_update','earning','deduction'));
    }
    public function salaryGeneration(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();

        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $department=[];
        if(!empty($request->segment(2))){
            $update = NoticeMaster::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
        }

        $users = [];

        if(isset($request->office_id)){
            $users = \DB::table('users')
            ->join('employee_infos', 'users.id', '=', 'employee_infos.user_id')
            ->join('position_masters', 'employee_infos.position_id', '=', 'position_masters.id')
            ->select('users.id', 'users.name', 'users.salary', 'employee_infos.office_id', 'employee_infos.department_id','employee_infos.position_id', 'employee_infos.shift_id', 'position_masters.position_name', 'employee_infos.employee_code')
            ->orderBy('employee_infos.department_id', 'asc')
            ->where('users.status', 'Active')
            ->where('employee_infos.employee_code', '!=', NULL)
            ->where('employee_infos.department_id', $request->department_id)
            ->where('employee_infos.office_id', $request->office_id)
            ->where('users.organisation_id', $user_id)->get();

            $month_filter = $request->month;
            $year_filter = $request->year;

            return view('organization.payroll_compensation.salary_generation',compact('organisation','office','department', 'users', 'month_filter', 'year_filter'));
        } else {
            $users = \DB::table('users')
            ->join('employee_infos', 'users.id', '=', 'employee_infos.user_id')
            ->join('position_masters', 'employee_infos.position_id', '=', 'position_masters.id')
            ->select('users.id', 'users.name', 'users.salary', 'employee_infos.office_id', 'employee_infos.department_id','employee_infos.position_id', 'employee_infos.shift_id', 'position_masters.position_name', 'employee_infos.employee_code')
            ->orderBy('employee_infos.department_id', 'asc')
            ->where('users.status', 'Active')
            ->where('employee_infos.employee_code', '!=', NULL)
            ->where('users.organisation_id', $user_id)->get();

            return view('organization.payroll_compensation.salary_generation',compact('organisation','office','department', 'users'));
        }

    }

    public function employeeSalarySlip(Request $request){

        if($request->user_id){

            $user_id = Auth::user()->id;
            $organisation = Organisation::where(['user_id'=>$user_id])->first();

            $data = \DB::table('salary_generates')
            ->join('employee_infos', 'salary_generates.user_id', '=', 'employee_infos.user_id')
            ->join('position_masters', 'employee_infos.position_id', '=', 'position_masters.id')
            ->join('users', 'users.id', '=', 'salary_generates.user_id')
            ->select('salary_generates.net_salary', 'salary_generates.present', 'salary_generates.absent', 'salary_generates.leave', 'salary_generates.incentive', 'salary_generates.bonus', 'salary_generates.earned_salary', 'salary_generates.deduction_salary', 'salary_generates.other_deduction', 'salary_generates.user_id', 'employee_infos.employee_code', 'employee_infos.office_id', 'employee_infos.position_id', 'position_masters.position_name', 'users.name', 'users.email', 'users.mobile', 'users.created_at')
            ->where('salary_generates.month_year', $request->month_year)
            ->whereIn('salary_generates.user_id', $request->user_id)
            ->where('employee_infos.employee_code', '!=', NULL)
            ->get();

            SalaryGenerate::whereIn('user_id', $request->user_id)->update(['status' => 1]);
            $month_year = $request->month_year;

           // dd($organisation);

            return view('organization.payroll_compensation.employeeSalarySlip',compact('month_year', 'data', 'organisation'));

        } else {
            return back();
        }
    }


    public function viewSalarySlip(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $month_year = date('Y-m', strtotime($request->month_year)); 
        if($request->user_id){
            $request->only_earn_salary = [];
            $request->all_deduction = [];
            $request->net_earn_salary = [];
            $request->bonus_incentive = [];
            $request->deduction_other = [];
  
            foreach ($request->user_id as $key => $userId) {
                $incentive = 0;
                $bonus = 0;
                $earn_salary = 0;
                $other_deduction = 0;

                $earn_salary += $request->earn_salary[$userId];
                $request->only_earn_salary[$userId] = $earn_salary;

                if($earn_salary != 0) {
                $info = EmployeeInfo::where('user_id', $userId)->where('employee_code', '!=', NULL)->select('office_id')->first();
                $SalaryMaster = SalaryMaster::where('office_id', $info->office_id)->where('earning_deduction', 2)->select('amount_percent')->get();
                    if(count($SalaryMaster) != 0){
                        foreach ($SalaryMaster as $value) {
                            $other_deduction +=  $earn_salary * ($value->amount_percent / 100);
                        }
                        $other_deduction = round($other_deduction, 2);
                    }
                }

                if($request->incentive[$userId]){
                    $earn_salary += $request->incentive[$userId];
                    $incentive = $request->incentive[$userId];
                }
                if($request->bonus[$userId]){
                    $earn_salary += $request->bonus[$userId];
                    $bonus = $request->bonus[$userId];
                }
                $earn_salary = round($earn_salary, 2);
                $abs_deduction = round($request->abs_deduction[$userId], 2);

                $already_genrated = SalaryGenerate::where('user_id', $userId)->where('month_year', $month_year)->select('id')->first();
                
                if($already_genrated){
                    // $SalaryGenerate = new SalaryGenerate();
                    // $already_genrated->user_id = $userId;
                    // $already_genrated->month_year = $month_year;
                    $already_genrated->net_salary = $request->net_salary[$userId];
                    $already_genrated->present = $request->present[$userId];
                    $already_genrated->absent = $request->absent[$userId];
                    $already_genrated->leave = $request->leave[$userId];
                    $already_genrated->incentive = $request->incentive[$userId];
                    $already_genrated->bonus = $request->bonus[$userId];
                    $already_genrated->earned_salary = $earn_salary;
                    $already_genrated->deduction_salary = $abs_deduction;
                    $already_genrated->other_deduction = $other_deduction;
                    $already_genrated->status = 0;
                    $already_genrated->created_by = $user_id;
                    $already_genrated->save();
                } else {
                    $SalaryGenerate = new SalaryGenerate();
                    $SalaryGenerate->user_id = $userId;
                    $SalaryGenerate->month_year = $month_year;
                    $SalaryGenerate->net_salary = $request->net_salary[$userId];
                    $SalaryGenerate->present = $request->present[$userId];
                    $SalaryGenerate->absent = $request->absent[$userId];
                    $SalaryGenerate->leave = $request->leave[$userId];
                    $SalaryGenerate->incentive = $request->incentive[$userId];
                    $SalaryGenerate->bonus = $request->bonus[$userId];
                    $SalaryGenerate->earned_salary = $earn_salary;
                    $SalaryGenerate->deduction_salary = $abs_deduction;
                    $SalaryGenerate->other_deduction = $other_deduction; 
                    $SalaryGenerate->created_by = $user_id;
                    $SalaryGenerate->save(); 
                }

                $request->all_deduction[$userId] = $abs_deduction;
                $request->net_earn_salary[$userId] = $earn_salary;
                $request->bonus_incentive[$userId] = $incentive + $bonus;
                $request->deduction_other[$userId] = $other_deduction; 

            }

        return view('organization.payroll_compensation.view_salary_slip',compact('organisation', 'request', 'month_year'));

        } else {
            return back();
        }
    }

    public function salaryApprovalFlow(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.payroll_compensation.salary_approval_flow',compact('organisation'));
    }
    public function incentiveCompensation(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.payroll_compensation.incentive_compensation',compact('organisation'));
    }
    public function advanceLoanDeduction(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.payroll_compensation.advance_loan_deduction',compact('organisation'));
    }
    public function investmentDeclaration(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.payroll_compensation.investment_declaration',compact('organisation'));
    }
    public function taxComputation(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.payroll_compensation.tax_computation',compact('organisation'));
    }
}
