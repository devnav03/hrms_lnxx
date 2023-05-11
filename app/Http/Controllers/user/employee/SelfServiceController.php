<?php

namespace App\Http\Controllers\user\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use App\Models\FormEngineCategory;
use App\Models\EmployeeInfo;
use App\Models\HiringApproval;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;
class SelfServiceController extends Controller
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
    public function Self(Request $request,$url){
        $user_data = Auth::user();
        $emp_code = $user_data->id;
        $organisation = Organisation::where(['user_id'=>$user_data->organisation_id])->first();
        
        $form_category = FormEngineCategory::select('id','name','is_multiple')->where('orgnization_id', $user_data->organisation_id)->where(['name'=>str_replace('-',' ',$url)])->first();
      
        $form_engine = DB::select("SELECT a.id,b.form_name,b.form_column,b.master_table,a.is_required,b.data_type,b.data_length,b.pattern,b.get_where,b.form_column_id,a.editable FROM `map_form_orgs` as a INNER JOIN form_engines as b on a.form_name=b.form_column WHERE organisation_id=$user_data->organisation_id AND b.form_category_id=$form_category->id ORDER BY b.id ASC");
       
       $employee_info = EmployeeInfo::select('update_data')->where('from_cat_id', $form_category->id)->where('user_id',$user_data->id)->first();
      
        return view('user.employee.emp_self_service',compact('organisation','emp_code','form_category','form_engine','user_data','employee_info'));
    }
    public function GetOrganisation($user_id){
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }

    public function AddEmployee(Request $request){
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $update = EmpDetail::where(['user_id'=>$user_id])->first();
        $organisation = $this->GetOrganisation($user_id);
        return view('user.employee.add_employee_details',compact('organisation','update','user_email'));
    }
    public function UpdateEmployee(Request $request){
        $user_id = Auth::user()->id;
        $select = EmpDetail::where(['user_id'=>$user_id])->first();
        if(!empty($select)){
            $select->first_name = $request->first_name;
            $select->last_name = $request->last_name;
            $select->gender = $request->gender;
            $select->dob = $request->dob;
            $select->father_name = $request->father_name;
            $select->mother_name = $request->mother_name;
            $select->salary = $request->salary;
            if(!empty($request->profile)){
                $fileName = strtolower($request->first_name).'_'.$user_id.'_'.preg_replace('/\s\s+/', ' ', $request->last_name).'.'.$request->profile->extension();
                $request->profile->move(public_path('employee/profile'),$fileName);
                $select->profile = $fileName;
            }
            $select->save();
            return redirect()->back()->with('success', 'Updated Successfully');   
        }  
    }

    public function SendRegisterMail($data){
        $email = $data->email;
        try {
            $orgnisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
            $template_data = ['email' => $data->email, 'name' => $data->first_name.' '.$data->last_name,'password'=>$data->password,'user_name'=>$orgnisation->user_name];
            Mail::send(['html'=>'email.account_registration'], $template_data,
                function ($message) use ($email) {
                    $message->to($email)->from('dipanshu.roy68@gmail.com')->subject('Account registration');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function EmployeeDetails(){
        $organisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
        return view('organization.employee.employee_details',compact('organisation'));
    }
    public function UpdateHiringStatus(Request $request){
        $hiring_approval = HiringApproval::where('id',$request->hiring_id)->first();
        $hiring_approval->approved_by = Auth::user()->id;
        $hiring_approval->status = $request->status;
        $hiring_approval->status_remark = $request->status_remark;
        $hiring_approval->save();
        return redirect('candidate-hiring-request')->with('success','Status Updated Successfully');
    }
    public function CandidateHiringRequest(Request $request){
        $user_id = Auth::user();
        $organisation = Organisation::where('user_id',$user_id->organisation_id)->first();
        if(!empty($request->check_status)){
            if($request->check_status=='Approved'){
                $status ='AND a.status=1';
            }elseif($request->check_status=='Rejected'){
                $status ='AND a.status=2';
            }else{
                $status ='AND a.status=3';
            }
        }else{
            $status ='AND a.status=3';
        }
        $rowdata = DB::select("SELECT a.id,b.status_name,a.office_id,a.employee_id,a.status,a.status_id,c.salutation,c.first_name,c.middle_name,c.last_name,a.approved_by,a.created_at,a.updated_at FROM `hiring_approvals` as a INNER JOIN interview_hiring_status as b on a.status_id=b.id INNER JOIN emp_details as c on a.candidate_id=c.id WHERE a.organisation_id=$user_id->organisation_id AND a.employee_id in ($user_id->id) $status ORDER BY a.id ASC");
        return view('user.employee.candidate_hiring_request',compact('organisation','rowdata'));
    }
    public function NotificationHistory(Request $request){
        $user_id = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user_id->organisation_id])->first();
        if(!empty($request->from_date) && !empty($request->to_date)){
            $result = DB::table('notifications_history')->where('user_id',$user_id->id)->whereBetween('created_at',[$request->from_date,$request->to_date])->orderBy('id','DESC')->get();
        }else{
            $result = DB::table('notifications_history')->where('user_id',$user_id->id)->orderBy('id', 'DESC')->get();
        }
        return view('user.employee.notitfication_history',compact('organisation','result'));
    }
}