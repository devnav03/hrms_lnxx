<?php

namespace App\Http\Controllers\organization\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\EmpContact;
use App\Models\EmpBank;
use App\Models\EmpCompany;
use App\Models\EmpEducations;
use App\Models\EmpDocument;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use App\Models\State;
use App\Models\OfficeMaster;
use App\Models\SourceMaster;
use App\Models\NoticeMaster;
use App\Models\EducationMaster;
use App\Models\BankMaster;
use App\Models\PositionMaster;
use App\Models\EmpReporting;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\EmployeeInfo;
use App\Models\DepartmentMaster;
use App\Models\FormEngineCategory;
use Illuminate\Support\Facades\Mail;
use DB;
class EmpController extends Controller
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
    public function AddEmp(Request $request,$id=null){
        $user_id = Auth::user()->id;
        $emp_code = $id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.employee.add_emp',compact('organisation','emp_code'));
    }
    public function UpdateEmp(Request $request,$from_cat_id,$user_id){
        $user_data = Auth::user();
        $emp_code = $user_id;
        $organisation = Organisation::where(['user_id'=>$user_data->id])->first();
        $form_category = FormEngineCategory::select('id','name','is_multiple')->where(['id'=>$from_cat_id])->first();
        $form_engine = DB::select("SELECT a.id,b.form_name,b.form_column,b.master_table,a.is_required,b.data_type,b.data_length,b.pattern,b.get_where,b.form_column_id FROM `map_form_orgs` as a INNER JOIN form_engines as b on a.form_name=b.form_column WHERE organisation_id=$user_data->id AND b.form_category_id=$from_cat_id ORDER BY b.id ASC");
        $employee_info = EmployeeInfo::select('update_data')->where('from_cat_id',$form_category->id)->where('user_id',$emp_code)->first();

        $user_in = User::where('id', $emp_code)->select('salary')->first();
        $shift_in = EmployeeInfo::where('user_id', $emp_code)->where('employee_code', '!=', NULL)->select('shift_id')->first();

        return view('organization.employee.update_emp',compact('organisation','emp_code','form_category','form_engine','user_data','employee_info', 'user_in', 'shift_in'));
    }
    public function AddEmployee(Request $request){
        // print_r($_POST);
        // die;
        // print_r($request->document);die;
        $state = State::select(['stateID','stateName'])->where(['countryID'=>'IND'])->orderBy('stateName', 'ASC')->get();
        $source_name = SourceMaster::select(['id','source_name'])->where(['status'=>'Active'])->orderBy('source_name', 'ASC')->get();
        $notice_period = NoticeMaster::select(['id','notice_days'])->where(['status'=>'Active'])->orderBy('notice_days', 'ASC')->get();
        $education_name = EducationMaster::select(['id','education_title'])->where(['status'=>'Active'])->orderBy('education_title', 'ASC')->get();
        $bank_name = BankMaster::select(['id','name'])->orderBy('name', 'ASC')->get();
        $designation_name = PositionMaster::select(['id','position_name'])->where(['status'=>'Active'])->orderBy('position_name', 'ASC')->get();
        if(!empty($_POST)){
            $users = new User();
            $users->name = $request->first_name.' '.$request->last_name;
            $users->email = $request->email;
            $users->type = 2;
            $users->password = Hash::make($request->password);
            $users->save();
        
            $employee = new EmpDetail();
            $employee->user_id = $users->id;
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->gender = $request->gender;
            $employee->dob = $request->dob;
            $employee->father_name = $request->father_name;
            $employee->mother_name = $request->mother_name;
            $employee->salary = $request->salary;
            $employee->source_id = $request->source_id;
            $employee->notice_id = $request->notice_id;
            $employee->designation_id = $request->designation_id;
            $employee->created_by = Auth::user()->id;
            $fileName = strtolower($request->first_name).'_'.$users->id.'.'.$request->profile->extension();
            $request->profile->move(public_path('employee/profile'),$fileName);
            $employee->profile = $fileName;
            $employee->save();
            $this->SendRegisterMail($request);

            $contact = new EmpContact();
            $contact->user_id = $users->id;
            $contact->mobile = $request->mobile;
            $contact->father_mobile = $request->father_mobile;
            $contact->friend_mobile = $request->friend_mobile;
            $contact->state_id = $request->state_id;
            $contact->city_id = $request->city_id;
            $contact->address = $request->address;
            $contact->pincode = $request->pincode;
            $contact->save();
            
           if($request->education_type){
            for($i=0; $i<count($request->education_type); $i++){
                $educatoin = new EmpEducations();
                $educatoin->user_id = $users->id;
                $educatoin->education_type = $request->education_type[$i];
                $educatoin->course_name = $request->course_name[$i];
                $educatoin->board_university = $request->board_university[$i];
                $educatoin->percentage_cgpa = $request->percentage_cgpa[$i];
                $educatoin->from_year = $request->from_year[$i];
                $educatoin->to_year = $request->to_year[$i];
                if(!empty($request->document[$i])){
                $fileName2 = strtolower($request->course_name[$i]).'_'.$users->id.'_'.preg_replace('/\s\s+/', ' ', $request->course_name[$i]).'.'.$request->document[$i]->extension();
                $request->document[$i]->move(public_path('employee/education'),$fileName2);
                $educatoin->document = $fileName2;
                }
                $educatoin->save();
            }
           }
            
            $empbank = new EmpBank();
            $empbank->user_id = $users->id;
            $empbank->acc_holder_name = $request->acc_holder_name;
            $empbank->bank_id = $request->bank_id;
            $empbank->acc_number = $request->acc_number;
            $empbank->ifsc_code = $request->ifsc_code;
            $empbank->pan_number = $request->pan_number;
            $empbank->branch_name = $request->branch_name;
            $empbank->save();

            if($request->comp_name){
                for($k=0; $k<count($request->comp_name); $k++){
                    $empcompany = new EmpCompany();
                    $empcompany->user_id = $users->id;
                    $empcompany->comp_name = $request->comp_name[$k];
                    $empcompany->designation = $request->designation[$k];
                    $empcompany->date_of_joining = $request->date_of_joining[$k];
                    $empcompany->date_of_resignation = $request->date_of_resignation[$k];
                    $empcompany->ctc = $request->ctc[$k];
                    $empcompany->reason_for_leav_comp = $request->reason_for_leav_comp[$k];
                    $empcompany->save();
                }
            }
            if($request->doucment_title){
                for($l=0; $l<count($request->doucment_title); $l++){
                    $empdocument = new EmpDocument();
                    $empdocument->user_id = $users->id;
                    $empdocument->doucment_title = $request->doucment_title[$l];

                    if(!empty($request->doucment_file[$l])){  
                        $fileName4 = strtolower($request->doucment_title[$l]).'_'.$users->id.'_'.preg_replace('/\s\s+/', ' ', $request->doucment_title[$l]).'.'.$request->doucment_file[$l]->extension();
                        $request->doucment_file[$l]->move(public_path('employee/documnet'),$fileName4);
                        $empdocument->doucment_file = $fileName4;
                    }
                    $empdocument->save();
                }
            }
            
            return redirect('add-employees')->with('success','Saved successfuly');
        }
        $organisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
        return view('organization.employee.add_employee_details',compact('organisation','state','source_name','notice_period','education_name','bank_name','designation_name'));
    }
    public function SendRegisterMail($data){
        $email = $data->email;
        try {
            $orgnisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
            $template_data = ['email' => $data->email, 'name' => $data->first_name.' '.$data->last_name,'password'=>$data->password,'user_name'=>$orgnisation->user_name];
            Mail::send(['html'=>'email.account_registration'], $template_data,
                function ($message) use ($email) {
                    $message->to($email)->from('vikas@shailersolutions.com')->subject('Account registration');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function EmployeeDetails(){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.employee.employee_details',compact('organisation'));
    }
    public function AttendanceDetails(){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.employee.attendance_details',compact('organisation'));
    }
    public function viewEmployeeAttendanceDetails(){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $employee_atten_details = DB::select("SELECT a.user_id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `emp_details` as a LEFT JOIN emp_attendances as b on a.user_id=b.user_id INNER JOIN users as c on c.id=a.user_id WHERE a.created_by=$user_id AND c.type=2 GROUP by a.id ORDER BY name ASC;");
        return view('organization.employee.view_employee_attendance_details',compact('organisation','employee_atten_details'));
    }
    public function viewEmployeeLeaveDetails(){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $leave_details = DB::select("SELECT a.user_id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `emp_details` as a LEFT JOIN leaves as b on a.user_id=b.user_id INNER JOIN users as c on c.id=a.user_id WHERE a.created_by=$user_id AND c.type=2 GROUP by a.id ORDER BY name ASC;");
        return view('organization.employee.view_employee_leave_details',compact('organisation','leave_details'));
    }
    public function employeeLeaveStatus(Request $request){
        $user_id = Auth::user()->id;
        $leaves = Leave::where('id',$request->id)->first();
        $leaves->status = $request->status;
        $leaves->updated_by = $user_id;
        $leaves->save();
        return response()->json(['status'=>200,'data' => $leaves]);
    }
    public function EmployeeReporting(Request $request){
        $user_id = Auth::user()->id;
        $update=[];
        $update_emp=[];
        if(!empty($request->segment(2))){
            $update = EmpReporting::where('id',$request->segment(2))->first();
            $update_emp = EmpDetail::select('user_id','first_name','last_name')->where('designation_id',$update->position_id)->where('created_by',$user_id)->orderBy('first_name', 'ASC')->get();
        }
        $organisation = Organisation::where(['user_id'=>$user_id])->first();

        $position_master = PositionMaster::select('id','position_name')->where('status','Active')->where('orgnization_id',$user_id)->get();

        $emp_detail = EmpDetail::select('user_id','first_name','last_name')->where('created_by',$user_id)->orderBy('first_name', 'ASC')->get();

        $emp_reportings = DB::select("SELECT a.id,a.orgnization_id,a.reporting_id,b.name as report_name,c.name as org_name,a.employee_id FROM `emp_reportings` as a INNER JOIN users as b on a.reporting_id=b.id INNER JOIN users as c on a.orgnization_id=c.id WHERE orgnization_id=$user_id ORDER BY a.id DESC;");
        if(!empty($request->position_id)){
            $emp_reporting = new EmpReporting();
            $emp_reporting->position_id = $request->position_id;
            $emp_reporting->orgnization_id = $user_id;
            $emp_reporting->reporting_id = $request->reporting_id;
            $emp_reporting->employee_id = json_encode($request->employee_id,JSON_NUMERIC_CHECK);
            $emp_reporting->save();
            return redirect('employee-reporting')->with('success','Saved successfuly');
        }
        return view('organization.employee.employee_reporting',compact('organisation','position_master','emp_detail','emp_reportings','update','update_emp'));
    }
    public function addLeave(request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $update=[];
        $users=[];
        $department=[];
        if(!empty($request->segment(2))){
            $update = Leave::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::select('id','department_name')->where('id',$update->department_id)->get();
            $users = DB::select("SELECT b.id,b.name,a.employee_code FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id AND a.employee_code IS NOT null AND b.id=$update->user_id");
        }
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->get();
        if(!empty($request->user_id)){
            $addLeaves = new Leave();
            $addLeaves->user_id = $request->user_id;
            $addLeaves->office_id = $request->office_id;
            $addLeaves->department_id = $request->department_id;
            $addLeaves->start_date = $request->start_date;
            $addLeaves->end_date = $request->end_date;
            $addLeaves->leave_type = $request->leave_type;
            $addLeaves->duration = $request->duration;
            $addLeaves->reason_for_leav_comp = $request->reason_for_leav_comp;
            $addLeaves->updated_by = $user_id;
            $addLeaves->save();
            return redirect('add-leave')->with('success','Saved successfuly');
        }
        return view('organization.employee.add_leave',compact('organisation','office','update','department','users'));
    }
    public function DeleteEmployees($id){
        $user_id = Auth::user()->id;
        User::where('id',$id)->delete();
        EmployeeInfo::where('organisation_id',$user_id)->where('user_id',$id)->delete();
        return redirect('employee-details')->with('success','Deleted successfuly');
    }
}
