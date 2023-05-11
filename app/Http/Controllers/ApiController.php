<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\LeaveAuthority;
use App\Models\PositionMaster;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\ApprovalFlow;
use App\Models\Organisation;
use App\Models\City;
use App\Models\EmpAttendance;
use App\Models\Leave;
use App\Models\ProjectActivity;
use App\Models\Timeseet;
use App\Models\EmpDetail;
use App\Models\SourceMaster;
use App\Models\NoticeMaster;
use App\Models\EducationMaster;
use App\Models\EmpDocument;
use App\Models\LetterTemplate;
use App\Models\ProjectMaster;
use App\Models\FormEngineCategory;
use App\Models\ShiftMaster;
use App\Models\LeaveType;
use App\Models\EmpType;
use App\Models\State;
use App\Models\EmployeeInfo;
use App\Models\AssignTask;
use App\Models\FormEngine;
use App\Models\WeekDay;
use App\Models\ShiftDuration;
use App\Models\PushNotificationHistory;
use App\Models\Notification;
use App\Models\LeaveApprovalSent;
use Auth;
use DB;

class ApiController extends Controller
{
    public function GetReportingUser($user_id){
        $reporting = DB::select("SELECT a.orgnization_id,a.reporting_id,b.email as report_email,b.name as report_name,c.name as org_name,c.email as org_email FROM `emp_reportings` as a INNER JOIN users as b on a.reporting_id=b.id INNER JOIN users as c on a.orgnization_id=c.id WHERE JSON_CONTAINS(a.employee_id,$user_id)=1");
        if(!empty($reporting[0])){
            return $reporting[0];
        }else{
            return array();
        }
    }
    public function SendAttendanceMail($data){
        $email = array($data->org_email, $data->report_email);
        try {
            $template_data = [
                'report_email'  => $data->report_email,
                'report_name'   => $data->report_name,
                'org_name'      => $data->org_name,
                'org_email'     => $data->org_email,
                'user_name'     => Auth::user()->name
            ];
            Mail::send(['html'=>'email.attendance'], $template_data,
                function ($message) use ($email,$template_data) {
                    $message->to($email)->from("dipanshu.roy68@gmail.com")->subject($template_data['user_name'].' marked attendance on '.date('d-M-Y'));
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }

    public function EmployeeLogin(Request $request) {

        // $this->ValidIn($request,['email','password']);

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        if (Auth::attempt($credentials))  {
        $user = User::select('id','email','status','password', 'remember_token')->where('email', $request->email)->first();

        if(!empty($user)){
            if($user->status=='Active'){
                if (Hash::check($request->password, $user->password)){

                    // $token = $user->createToken('Login Successfully')->accessToken;
                    
                    $api_key = $this->generateApiKey();
                    if($user->remember_token){
                        $api_key = $user->remember_token; 
                    } else {
                    User::where('email', $user->id)
                    ->update([
                    'remember_token' => $api_key,
                     ]);
                    }

                    $usersdata = User::select('id','name','email','mobile','type','status','remember_token','created_at', 'lnxx_login')->where('id',$user->id)->first();
                    $usersdata->remember_token = $api_key;
                    $usersdata->fcm_id =  $request->fcm_id;
                    $usersdata->save();

                    $setuser['id']      = $usersdata->id;
                    $setuser['name']    = $usersdata->name;
                    $setuser['email']   = $usersdata->email;
                    $setuser['mobile']  = $usersdata->mobile;
                    $setuser['type']    = $usersdata->type;
                    $setuser['fcm_id']  = $usersdata->fcm_id;
                    $setuser['status']  = $usersdata->status;
                    $setuser['api_key']   = $api_key;
                    $setuser['date'] = date_format(date_create($usersdata->created_at),"d-M-Y H:i");
                    if($usersdata->lnxx_login == 1){
                        $setuser['lnxx_login'] = $usersdata->lnxx_login;
                    } else {
                        $setuser['lnxx_login'] = 0;
                    }
                    $emp = EmployeeInfo::where('user_id', $user->id)->where('from_cat_id', 1)->select('update_data')->first();
                    $obj = json_decode($emp->update_data);
                    $url = route('get-started');
                    if($obj->profile){
                       $setuser['profile'] = $url.'/'.$obj->profile; 
                    } else {
                       $setuser['profile'] = '';
                    }
                    
                    if($usersdata->lnxx_login == 1){
                        $curl = curl_init();  
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://vztor.in/api/v1/lead-token',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array('email' => $request->email,'mobile'=> $usersdata->mobile, 'token'=>$usersdata->remember_token),));
                        $response = curl_exec($curl);
                        curl_close($curl);
                    }

                    $response = ["status"=>200,"message" => "Login Successfully","data" => $setuser];
                    return response()->json($response);
                } else {
                    $response = ["status"=>422,"message" => "Password not matched","data"=>null];
                    return response()->json($response);
                }
            } else{
                $response = ["status"=>422,"message" => "Your account is deactive please contact to you admin","data"=>null];
                return response()->json($response);
            }
        } else {
            $response = ["status"=>422,"message" =>"User does not exist","data"=>null];
            return response()->json($response);
        }
    } else {
        $response = ["status"=>422,"message" =>"Incorrect credentials","data"=>null];
        return response()->json($response);
    }
    }

    public function TodayAttendance(Request $request){
        $user = User::where('remember_token', $request->token)->select('id')->first();
        if($user){
        $date = date('Y-m-d');
        $user_id = $user->id;
        $data = EmpAttendance::where('user_id', $user_id)->whereRaw('date_format(created_at,"%Y-%m-%d")'."='".$date . "'")->select('in_time', 'out_time', 'total_time')->first();
        return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);

        }
    }
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }
    
    public function UserProfile(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'name', 'email', 'mobile', 'shift_id', 'profile_image')->first();
        if($user){

           $data['name'] = $user->name;
           $data['email'] = $user->email;
           $data['mobile'] = $user->mobile;
           
        $emp = EmployeeInfo::where('user_id', $user->id)->where('from_cat_id', 1)->select('update_data', 'employee_code', 'office_id', 'department_id', 'position_id')->first();

        //dd($emp);
        $url = route('get-started');
        if($user->profile_image){
            $data['profile'] = $url.'/'.$user->profile_image; 
        } else {
            $obj = json_decode($emp->update_data);
            if($obj->profile){
               $data['profile'] = $url.'/'.$obj->profile; 
            } else {
               $data['profile'] = '';
            }
        }

        $pos = PositionMaster::where('id', @$emp->position_id)->select('position_name')->first();
        $data['designation'] = @$pos->position_name;
        $pos = OfficeMaster::where('id', @$emp->office_id)->select('office_name')->first();
        $data['office'] = @$pos->office_name;
        $pos = DepartmentMaster::where('id', @$emp->department_id)->select('department_name')->first();
        $data['department'] = @$pos->department_name;

        return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        } 
    }

    public function cancel_leave(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
            $user_id = $user->id;
            $dt = date('Y-m-d');
            $leave = Leave::where('id', $request->leave_id)->where('user_id', $user_id)->select('start_date')->first();
            if($dt < $leave->start_date) {
            $leaves = Leave::where('id', $request->leave_id)->where('user_id', $user_id)->first();
            $leaves->status = 'Canceled';
            $leaves->updated_by = $user_id;
            $leaves->save();
            return response()->json(['success' => true, 'status' => 200, 'message'=> 'Leave  successfully withdraw']);
        } else {
            return response()->json(['success' => false, 'status' => 201, 'message'=> 'Stipulated time for cancellation of leave request is over. Now you can not cancel your leave application.']);
        }
        }
    }

    public function task_list(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id')->first();
        if($user){
            
            $emp = EmployeeInfo::where('user_id', $user->id)->where('employee_code', '!=', NULL)->select('id')->first();
            $data = [];
            if($emp){
               $data = \DB::table('assign_tasks')
                        ->join('project_masters', 'assign_tasks.project_id', '=', 'project_masters.id')
                        ->select('project_masters.id', 'project_masters.project_name')
                        ->orderBy('project_masters.id', 'desc')
                        ->where('project_masters.status', 'Active')
                        ->where('assign_tasks.status', '!=', 'Close')
                        ->where('assign_tasks.user_id', $emp->id)->groupBy('project_masters.id')->get(); 
            }
            
            return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }

    public function project_activities(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id')->first();
        if($user){

            $data = ProjectActivity::where('project_id', $request->project_id)->select('id', 'activity_name')->get();
            return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);

        }
    }
    
    public function timesheet_status(){
        $data = [];
        $data[0]['id'] = 1;
        $data[0]['name'] = 'Pending';

        $data[1]['id'] = 2;
        $data[1]['name'] = 'In Process';

        $data[2]['id'] = 3;
        $data[2]['name'] = 'Complete';
        return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
    }
    
    public function submit_timesheet(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
            $Timeseet = new Timeseet();
            $Timeseet->user_id = $user->id;
            $Timeseet->orgnization_id = $user->organisation_id;
            $Timeseet->project_id = $request->project_id;
            $Timeseet->activity_id = $request->activity_id;
            $Timeseet->start_time = $request->start_time;
            $Timeseet->end_time = $request->end_time;
            $Timeseet->duration = $request->duration;
            $Timeseet->description = $request->description;
            $Timeseet->status = $request->status;
            $Timeseet->save();
            return response()->json(['success' => true, 'status' => 200, 'message'=> 'Timesheet successfully submit']);
        }
    }

    public function timesheet_history_details(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
                $timeseet = Timeseet::where('user_id', $user->id)->where('id', $request->timeseet_id)->first();
               $project =  ProjectMaster::where('id', $timeseet->project_id)->select('project_name')->first();
                    $slide['id'] = $timeseet->id;
                    $slide['project'] = $project->project_name;

                    $activity = ProjectActivity::where('id', $timeseet->activity_id)->select('activity_name')->first();
                    $slide['activity'] = $activity->activity_name;
                    $slide['date'] = date('d M, Y', strtotime($timeseet->created_at));
                    $slide['start_time'] = $timeseet->start_time;
                    $slide['end_time'] = $timeseet->end_time;
                    $slide['duration'] = $timeseet->duration;
                    $slide['description'] = $timeseet->description;
                    
                    $status = '';
                    if($timeseet->status == 1){
                        $status = 'Pending';
                    }
                    if($timeseet->status == 2){
                        $status = 'In Process';
                    }
                    if($timeseet->status == 3){
                        $status = 'Complete';
                    }
                    $slide['status'] = $status;
                    $data[] = $slide;
            return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }

    public function timesheet_history(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();

        if($user){
            
            if(isset($request->days)){ 
                $date = \Carbon\Carbon::today()->subDays($request->days);
                $timeseets = Timeseet::where('user_id', $user->id)->where('created_at','>=', $date)->orderBy('id', 'desc')->get();
            } else {
                $timeseets = Timeseet::where('user_id', $user->id)->orderBy('id', 'desc')->get();
            }
            $data = [];

            if($timeseets){
                foreach ($timeseets as $timeseet) {
                    $slide['id'] = $timeseet->id;
                    $project =  ProjectMaster::where('id', $timeseet->project_id)->select('project_name')->first();
                    $slide['project'] = $project->project_name;
                    $activity = ProjectActivity::where('id', $timeseet->activity_id)->select('activity_name')->first();
                    $slide['activity'] = $activity->activity_name;
                    $slide['date'] = date('d M, Y', strtotime($timeseet->created_at));
                    $slide['start_time'] = $timeseet->start_time;
                    $slide['end_time'] = $timeseet->end_time;
                    $slide['duration'] = $timeseet->duration;
                    $slide['description'] = $timeseet->description;
                    
                    $status = '';
                    if($timeseet->status == 1){
                        $status = 'Pending';
                    }
                    if($timeseet->status == 2){
                        $status = 'In Process';
                    }
                    if($timeseet->status == 3){
                        $status = 'Complete';
                    }
                    $slide['status'] = $status;

                    $data[] = $slide;
                } 
            }

            return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }

    public function change_password(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'password', 'lnxx_login', 'email', 'mobile')->first();
        if($user){
            $inputs = $request->all();
            $psw = $inputs['password'];
            $password = \Hash::make($inputs['password']);  
            $old_password = \Hash::make($inputs['old_password']);

            if (!\Hash::check($request->old_password, $user->password)){

            $message = "Old password not match";
            // return apiResponseAppmsg(false, 200, $message, null, null);
            return response()->json(['success' => false, 'status' => 201, 'message'=> $message]);

            } else {
              $id = $user->id;
              unset($inputs['password']);
                // $inputs = $inputs + ['password' => $password];
                //(new User)->store($inputs, $id);

            User::where('id', $id)->update(['password' => $password]);
            if($user->lnxx_login == '1') {
                $curl = curl_init();  
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://vztor.in/api/v1/insert-pasw',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('email' => $user->email, 'mobile' => $user->mobile, 'password' => $psw),));
                $response = curl_exec($curl);
                //echo "<pre>"; print_r($response); echo "</pre>"; die; 
                curl_close($curl);
            }
            $message = "Password successfully Changed";
            return response()->json(['success' => true, 'status' => 200, 'message'=> $message]);
                // return apiResponseAppmsg(true, 200, $message, null, null);
            }
        }
    }
    
    public function leaves_details(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
            $user_id = $user->id;
            $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.status,b.name,a.created_at FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id WHERE a.user_id=$user_id AND a.id=$request->leave_id");

            return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }

    public function leaves_history(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
            $user_id = $user->id;

            $data = [];
            
            if(isset($request->days)){
               
            $date = \Carbon\Carbon::today()->subDays($request->days);

            $data = \DB::table('leaves')
                        ->join('leave_types', 'leaves.leave_type', '=', 'leave_types.id')
                        ->select('leaves.id', 'leaves.start_date', 'leaves.end_date', 'leaves.duration', 'leaves.reason_for_leav_comp', 'leaves.status', 'leave_types.name', 'leaves.created_at')
                        ->orderBy('leaves.id', 'desc')
                         ->where('leaves.created_at','>=',$date)
                        ->where('leaves.user_id', $user_id)->get();

            } else {

            $data = \DB::table('leaves')
                        ->join('leave_types', 'leaves.leave_type', '=', 'leave_types.id')
                        ->select('leaves.id', 'leaves.start_date', 'leaves.end_date', 'leaves.duration', 'leaves.reason_for_leav_comp', 'leaves.status', 'leave_types.name', 'leaves.created_at')
                        ->orderBy('leaves.id', 'desc')
                        ->where('leaves.user_id', $user_id)->get();

            }

            // $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.status,b.name FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id WHERE a.user_id=$user_id ORDER BY a.id DESC");

            
           return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }

    public function leave_apply(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
            $user_id = $user->id;

            $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
            $emp = EmployeeInfo::select('office_id','department_id')->where('organisation_id',$user->organisation_id)->where('employee_code','!=','')->where('user_id',$user->id)->first();
            $date=date('Y-m-d');
            $data = LeaveType::select('id','name','total_leave')->where('orgnization_id', $user->orgnization_id)->where('department_id',$emp->department_id)->where('office_id',$emp->office_id)->get();
            $leave_type=array();
            foreach($data as $rows){
                $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$user->id AND YEAR(created_at)='$date' LIMIT 1");
                if(!empty($select[0]->leave_type)){
                    $rows->totalleave = $rows->total_leave - $select[0]->leave_type;
                } else{
                    $rows->totalleave = $rows->total_leave;
                }
                $leave_type[] = $rows;
            }  

            $takeLeave = new Leave();
            $takeLeave->user_id = $user->id;
            $takeLeave->office_id = $emp->office_id;
            $takeLeave->department_id = $emp->department_id;
            $takeLeave->start_date = $request->start_date;
            $takeLeave->end_date = $request->end_date;
            $takeLeave->duration = $request->duration;
            $takeLeave->leave_type = $request->leave_type;
            $takeLeave->reason_for_leav_comp = $request->reason_for_leav_comp;
            $takeLeave->save();
            $approvers = $this->Approvers($user,$takeLeave);
            if(!empty($approvers)){
                foreach($approvers as $rows){
                    $this->SendApproversMail($rows->user_id,$takeLeave);
                }
            }

            return response()->json(['success' => true, 'status' => 200, 'message'=> 'Successfully apply for leave']); 
        }
    }

    public function Approvers($user,$leaves){
        $emp = EmployeeInfo::select('position_id')->where('organisation_id',$user->organisation_id)->whereNotNull('employee_code')->where('user_id',$user->id)->first();
        $approval_flow = ApprovalFlow::select('flow_id')->where('orgnization_id',$user->organisation_id)->where('office_id',$leaves->office_id)->where('department_id',$leaves->department_id)->where('leave_type',$leaves->leave_type)->where('position_id',$emp->position_id)->first();
        if(!empty($approval_flow)){
            $leave_uthority =  LeaveAuthority::select('user_id')->where('flow_id',$approval_flow->flow_id)->where('orgnization_id',$user->organisation_id)->where('office_id',$leaves->office_id)->where('department_id',$leaves->department_id)->where('position_id',$emp->position_id)->get();
            if(!empty($leave_uthority)){
                return $leave_uthority;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function SendApproversMail($user_id,$takeleave){
        $users = User::where(['id'=>$user_id])->first();
        if(!empty($users)){
            $applied = User::select('name')->where(['id'=>$takeleave->user_id])->first();
            if(!empty($applied)){
                $leave_tyle = LeaveType::select('name')->where(['id'=>$takeleave->leave_type])->first();

                $LeaveApprovalSent =  (new LeaveApprovalSent);
                $LeaveApprovalSent->user_id = $users->id;
                $LeaveApprovalSent->leave_id = $takeleave->id;
                $LeaveApprovalSent->status = 0;
                $LeaveApprovalSent->save();

                $leav_token = \encrypt($LeaveApprovalSent->id);

                $template = [
                    'applied_name'  =>$applied->name,
                    'name'          =>$users->name,
                    'email'         =>$users->email,
                    'leave_type'    =>$leave_tyle->name,
                    'from'          =>$takeleave->start_date,
                    'to'            =>$takeleave->end_date,
                    'reason_for'    =>$takeleave->reason_for_leav_comp,
                    'leav_token'    =>$leav_token,
                ];
                try {
                    Mail::send(['html'=>'email.leave_approvers'], $template,
                        function ($message) use ($template) {
                            $message->to($template['email'])->from('lnxxapp@gmail.com')->subject($template['applied_name'].' '.$template['leave_type'].' Approval From '.$template['from'].' To '.$template['to'].'');
                    });
                    return true;
                } catch (Exception $ex) {
                    return false;
                }
            }
        }
    }

    public function leave_type(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id', 'organisation_id')->first();
        if($user){
            $user_id = $user->id;

            $emp = EmployeeInfo::select('office_id','department_id')->where('organisation_id',$user->organisation_id)->where('employee_code','!=','')->where('user_id',$user->id)->first();
            $date=date('Y-m-d');
            $data = LeaveType::select('id','name','total_leave')->where('orgnization_id', $user->organisation_id)->where('department_id',$emp->department_id)->where('office_id',$emp->office_id)->get();
            $leave_type=array();
            foreach($data as $rows){

                // $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$user->id AND YEAR(created_at)='$date' LIMIT 1");

                $year = date('Y');

                $select = Leave::where('leave_type', $rows->id)->where('status', 'Approved')->where('user_id', $user->id)->whereRaw('date_format(created_at,"%Y")'."='".$year."'")->select('duration')->get();

                if(!empty($select)){
                    $already_leave = 0;

                    foreach ($select as $sel) {
                        $already_leave += $sel->duration;
                    }
          
                    $rows->total_leave = $rows->total_leave - $already_leave;

                } else {
                    $rows->total_leave = $rows->total_leave;
                }
                $leave_type[] = $rows;
            }
            return response()->json(['success' => true, 'status' => 200, 'data'=> $leave_type]);
        }
    }
    
    public function updateProfile(Request $request){
        $user = User::where('remember_token', $request->api_key)->select('id')->first();
        if($user){
            $user_id = $user->id;
            
            $inputs = $request->all();
            $image = '';

            if($request->profile){
                $image = 'attach_' . time() . '.png';
                file_put_contents(public_path().'/employee/profile/'.$image, base64_decode($request->profile));
                $image = '/employee/profile/'.$image;
            }

            if($image){
                $emp = EmployeeInfo::where('user_id', $user->id)->where('from_cat_id', 1)->select('update_data', 'id')->first();
                $obj = json_decode($emp->update_data);
                $obj->profile = $image;
                $data = json_encode($obj);
                User::where('id', $user->id)->update([
                    'profile_image' => $image,
                ]);

                // EmployeeInfo::where('id', $emp->id)->update([
                //     'update_data' => $data, 
                // ]);

                return response()->json(['success' => true, 'status' => 200, 'message'=> 'Profile successfully updated']);
            }

            return response()->json(['success' => false, 'status' => 201, 'message'=> 'Profile not updated']);
        }
    }

    public function acknowledge_notification(Request $request){
        $user = User::where('remember_token', $request->token)->select('id')->first();
        if($user){
            $user_id = $user->id;
            PushNotificationHistory::where('id', $request->id)->update([
                    'is_view' => 1,
            ]);
            return response()->json(['success' => true, 'status' => 200, 'message'=> 'Notification successfully acknowledged']);

        }
    }

    public function push_notification_list(Request $request){
        $user = User::where('remember_token', $request->token)->select('id')->first();
        if($user){
            $user_id = $user->id;
            $date = \Carbon\Carbon::today()->subDays(7);
            $history = PushNotificationHistory::where('notification_id', $user_id)->where('is_view', 0)->where('created_at','>=',$date)->select('id', 'notification_id')->get();
            
            $data = [];
            $home = route('get-started');
            if(count($history) != 0){
                foreach ($history as $his) {
                    $slide['id'] = $his->id;

                    $noti = Notification::where('id', $his->notification_id)->select('image', 'description', 'title')->first();
                    $slide['title'] = $noti->title;

                    $slide['description'] = $noti->description;
                    if($noti->image){
                        $slide['image'] = $home.$noti->image;
                    } else {
                        $slide['image'] = '';
                    }
                    
                    $data[] = $slide;
                }
            }
           return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }

    public function AttendanceHistory(Request $request){
        $user = User::where('remember_token', $request->token)->select('id')->first();
        if($user){
            $user_id = $user->id;
            $data = [];
            $attendances = [];
            if(isset($request->days)){
               
            $date = \Carbon\Carbon::today()->subDays($request->days);
            $attendances = EmpAttendance::where('user_id', $user_id)->where('created_at','>=',$date)->select('in_time', 'out_time', 'total_time', 'in_image', 'out_image', 'created_at')->orderBy('id', 'desc')->get();
            } else {

            $attendances = EmpAttendance::where('user_id', $user_id)->select('in_time', 'out_time', 'total_time', 'in_image', 'out_image', 'created_at')->orderBy('id', 'desc')->get();
            }
            if($attendances){
                $url = route('get-started');
                foreach ($attendances as $attendance) {
                    $slide['in_time'] = $attendance->in_time;
                    $slide['out_time'] = $attendance->out_time;
                    $slide['total_time'] = $attendance->total_time;
                    $slide['in_image'] = $url.'/employee/attendance/'.$attendance->in_image;
                    if($attendance->out_image){
                    $slide['out_image'] = $url.'/employee/attendance/'.$attendance->out_image; 
                    } else {
                    $slide['out_image'] = null;
                    }
                    $slide['date'] = date('d-M-Y', strtotime($attendance->created_at));
                    $data[] = $slide;
                }
            }
            return response()->json(['success' => true, 'status' => 200, 'data'=> $data]);
        }
    }
    

    public function MarkAttendance(Request $request){
        // $this->ValidIn($request,['token','snapshot','latitude','longitude']);
        $user = User::select('id', 'name', 'shift_id')->where('remember_token', $request->token)->first();
        
        if(!empty($user)){
            $date = date('Y-m-d');
            $curren_time = date('H:i:s');
            $user_id = $user->id;

            // if($request->hasFile('snapshot')){
                // $imageName = str_replace(' ', '_', $user->name).'_'.$user_id.'.'.$request->snapshot->extension();

                 $imageName = 'attach_' . time() . '.png';
               // $request->snapshot->move(public_path('employee/attendance'),$imageName);
                file_put_contents(public_path().'/employee/attendance/'.$imageName, base64_decode($request->snapshot));
            // }

            if(empty($request->latitude) && empty($request->longitude)){
               // return response()->json(['status'=>400,'message'=>'Please Turn On Your Location']);
                return response()->json(['success' => false, 'status' => 201, 'message'=>'Please Turn On Your Location']);
                exit;
            }
            $attendance = DB::select("SELECT id,TIMEDIFF('$curren_time',in_time) as totaltime from `emp_attendances` WHERE DATE(created_at) = '$date' AND user_id=$user_id LIMIT 1");
            if(!empty($attendance[0])){
                
                $emp_attendance = EmpAttendance::where(['id'=>$attendance[0]->id])->first();
                $emp_attendance->user_id = $user_id;
                $emp_attendance->out_time = $curren_time;
                $emp_attendance->out_image = $imageName;
                $emp_attendance->out_latitude = $request->latitude;
                $emp_attendance->out_longitude = $request->longitude;
                $emp_attendance->total_time = $attendance[0]->totaltime;
                $emp_attendance->save();
                // return response()->json(['status'=>200,'message'=>'Successfully Attancdance Marked Out']);
                return response()->json(['success' => true, 'status' => 200, 'message'=>'Successfully Attendance Marked Out']);
            } else {
                $shift = ShiftDuration::where('shift_id', $user->shift_id)->select('in_time_relaxation', 'out_time_relaxation')->first();

                $emp_attendance = new EmpAttendance();
                $emp_attendance->user_id = $user_id; 
                $emp_attendance->in_time = $curren_time;
                $emp_attendance->in_image = $imageName;
                $emp_attendance->in_latitude = $request->latitude;
                $emp_attendance->in_longitude = $request->longitude;
                $emp_attendance->start_date = @$shift->in_time_relaxation;  
                $emp_attendance->end_date = @$shift->out_time_relaxation; 
                $emp_attendance->save();

                // $reporting = $this->GetReportingUser($user_id);
                // $this->SendAttendanceMail($user,$reporting);
                // return response()->json(['status'=>200,'message'=>'Successfully Attancdance Marked In']);

                return response()->json(['success' => true, 'status' => 200, 'message'=>'Successfully Attendance Marked In']);
            }
        } else {
            $response = ["status"=>422,"message" =>"User not found","data"=>null];
            return response()->json($response);
        }
    }


    public function ValidIn($request,$required_fields){
        foreach ($required_fields as $key => $value) {
            if(empty($request[$value])){
                $dataresponce=['Api_status'=>422,'msg'=> $value.' (POST) is missing'];
                echo json_encode($dataresponce);exit;
            }
        }
    }
    public function SaveContact(Request $request){
        $this->ValidIn($request,['salutation','first_name','last_name','email','mobile','date_of_birth']);
        $select = EmpDetail::where('email',$request->email)->first();
        if(empty($select)){
            $emp = new EmpDetail();
            $emp->salutation = $request->salutation;
            $emp->first_name = $request->first_name;
            $emp->middle_name = $request->middle_name;
            $emp->last_name = $request->last_name;
            $emp->email = $request->email;
            $emp->mobile = $request->mobile;
            $emp->dob = date('Y-m-d',strtotime($request->date_of_birth));
            $emp->resume = $request->resume;
            $emp->save();
            $response = ["status"=>200,"message" =>"Saved Successfully","data"=>$emp];
        } else{
            $response = ["status"=>422,"message" =>"This email already exists","data"=>null];
        }
        return response()->json($response);
    }
    public function GetEmp(Request $request){
        // $email = \encrypt($request->uri(3));
        // $mobile = \encrypt($request->uri(4));
        // $time = \encrypt($request->uri(5));
        // $url="https://vztor.in/lead-management-system/".$email."/".$time."/".$mobile."";
        // $response = ["status"=>200,"url"=>$url];
        // $email = \decrypt($_GET['email']);
        // $select = DB::select("SELECT a.id,a.mobile,a.password,b.employee_code,b.datas FROM `users` as a INNER JOIN employee_infos as b on a.id=b.user_id WHERE b.employee_code IS NOT null AND a.email='$email'");
        // if(!empty($select[0])){
        //     $data['employee_code']=$select[0]->employee_code;
        //     $datax = json_decode($select[0]->datas);
        //     $data['id']=$select[0]->id;
        //     $data['mobile']=$select[0]->mobile;
        //     $data['employee_code']=$datax->employee_code;
        //     $data['first_name']=$datax->first_name;
        //     $data['second_name']=$datax->second_name;
        //     $data['email']=$datax->email;
        //     $data['password']=$select[0]->password;
        //     $data['dob']=$datax->dob;
        //     $data['gender']=$datax->gender;
        //     $data['profile']=$datax->profile;
        //     $response = ["status"=>422,"message" =>"Saved Successfully","data"=>$data];
        // }else{
        //     $response = ["status"=>422,"message" =>"User not found","data"=>null];
        // }
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://lnxx-hrms.sspl20.com/api/save-contact',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('salutation' => 'MR','first_name' => 'Rajdeep','middle_name' => '','last_name' => 'Saxena','email' => 'rajdeep@gmail.com','mobile' => '9934277280','resume' => 'https://sspl20.com:2083/cpsess6950107302/frontend/paper_lantern/filemanager/index.html','date_of_birth' => '17-07-1996'),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;

        return response()->json($response);
    }
    public function FetchRequirementDetails(Request $request){
        $query ='';
        if(!empty($request->job_title)){
            $query .="AND a.job_title='$request->job_title'";
        }
        if(!empty($request->minimum_salary)){
            $query .="AND a.minimum_salary>='$request->minimum_salary'";
        }
        if(!empty($request->maximum_salary)){
            $query .="AND a.maximum_salary<='$request->maximum_salary'";
        }
        $requirement = DB::select("SELECT a.id,a.job_title,a.no_of_vacancy,a.minimum_salary,a.maximum_salary,a.job_type,a.description,b.office_name,c.department_name,d.position_name,b.address FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.job_type='$request->job_type' $query ORDER by a.id");
        return response()->json(['data' => $requirement]);
    }
}
