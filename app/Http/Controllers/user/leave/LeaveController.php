<?php

namespace App\Http\Controllers\user\leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use App\Models\LeaveAuthority;
use App\Models\ApprovalFlow;
use App\Models\EmployeeInfo;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveApprovalSent;
use DB;
use Illuminate\Support\Facades\Mail;
class LeaveController extends Controller
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
    public function GetOrganisation($user_id){
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
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
                            $message->to($template['email'])->from('vikas@shailersolutions.com')->subject($template['applied_name'].' '.$template['leave_type'].' Approval From '.$template['from'].' To '.$template['to'].'');
                    });
                    return true;
                } catch (Exception $ex) {
                    return false;
                }
            }
        }
    }

    public function TakeLeave(Request $request){
        $user = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        $emp = EmployeeInfo::select('office_id','department_id')->where('organisation_id',$organisation->user_id)->where('employee_code','!=','')->where('user_id',$user->id)->first();
        $date=date('Y-m-d');
        $data = LeaveType::select('id','name','total_leave')->where('orgnization_id',$organisation->user_id)->where('department_id',$emp->department_id)->where('office_id',$emp->office_id)->get();
        $leave_type=array();
        foreach($data as $rows){
            $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$user->id AND YEAR(created_at)='$date' LIMIT 1");
            if(!empty($select[0]->leave_type)){
                $rows->totalleave = $rows->total_leave - $select[0]->leave_type;
            }else{
                $rows->totalleave = $rows->total_leave;
            }
            $leave_type[] = $rows;
        }
        if(!empty($_POST)){
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
            return redirect('leave-history')->with('success','Saved successfuly');
        }
        return view('user.leave.take_leave',compact('organisation','leave_type'));
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
    public function LeaveHistory(Request $request){
        $user = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        return view('user.leave.leave_history',compact('organisation'));
    }
}