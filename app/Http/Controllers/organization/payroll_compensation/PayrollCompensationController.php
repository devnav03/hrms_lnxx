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
use DB;
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
        }else{
            $earning = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=1 ORDER BY a.id,a.header_name DESC");
            $deduction = DB::select("SELECT a.id,a.header_name,a.earning_deduction,a.amount_percent,a.created_at,b.office_name FROM `salary_masters` as a INNER JOIN office_masters as b on a.office_id=b.id WHERE a.orgnization_id=$user_id->id AND a.earning_deduction=2 ORDER BY a.id,a.header_name DESC");
        }
        if(!empty($request->office_id) && !empty($request->header_name)){
            if(!empty($request->upd_id)){
                $select = SalaryMaster::where('id',$request->upd_id)->first();
                $select->orgnization_id = $user_id->id;
                $select->office_id = $request->office_id;
                $select->header_name = $request->header_name;
                $select->earning_deduction = $request->earning_deduction;
                $select->amount_percent = $request->percentage;
                $select->save();
                return redirect('salary-head-master')->with('success','Updated successfuly');
            }else{
                $error_up = SalaryMaster::select('header_name')->where('office_id',$request->office_id)->where('header_name',$request->header_name)->where('earning_deduction',$request->earning_deduction)->first();
                if(!empty($error_up)){
                    return redirect('salary-head-master')->with('error',$error_up->header_name.' Salary header already exist');
                }
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
        return view('organization.payroll_compensation.salary_generation',compact('organisation','office','department'));
    }
    public function viewSalarySlip(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.payroll_compensation.view_salary_slip',compact('organisation'));
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
