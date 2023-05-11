<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Organisation;
use App\Models\LeaveType;
use App\Models\EmployeeInfo;
use App\Models\Leave;
use App\Models\EmpAttendance;
use DB;
class HomeController extends Controller
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
    public function index() {
        $login_from = \Session::get('login_from');
        $users = Auth::user();
        //dd($users->type);
        if($users->type == 'superadmin') {
                return view('superadmin.home');
        } else {
        if($login_from == $users->id || $login_from == $users->organisation_id){
        $link = Organisation::where('user_id', $login_from)->select('status')->first();
        if(@$link->status == 'Active'){
            if($users->type == 'superadmin') {
                return view('superadmin.home');
            } elseif($users->type == 'organization'){
                $organisation = Organisation::where(['user_id'=>$users->id])->first();
                return view('organization.home',compact('organisation'));
            } elseif($users->type == 'user'){
                $leaves = $this->leaves($users);
                $date = date('Y-m-d');
                $emp_attendance = EmpAttendance::select('in_time','out_time')->where('user_id',$users->id)->whereDate('created_at','=',$date)->first();
                $organisation = Organisation::where(['user_id'=>$users->organisation_id])->first();
                return view('user.home',compact('organisation','leaves','emp_attendance'));
            }
        } else {
            \Auth::logout();
            \Session::flush();
            $link = Organisation::where('user_id', $login_from)->select('user_name')->first();
            if(@$link->user_name){
            return redirect(@$link->user_name)->with('organisation_inactive', 'organisation_inactive');
           } else {
            return redirect('/');
           }
        }
    } else {
        \Auth::logout();
        \Session::flush();
        $link = Organisation::where('user_id', $login_from)->select('user_name')->first();
        return redirect(@$link->user_name);
    }
    }
    }
    public function leaves($users){
        $date=date('Y');
        $emp = EmployeeInfo::select('department_id','office_id')->where('user_id',$users->id)->where('organisation_id',$users->organisation_id)->whereNotNull('employee_code')->first();
        $data = LeaveType::select('id','name','total_leave')->where('orgnization_id',$users->organisation_id)->where('department_id',$emp->department_id)->where('office_id',$emp->office_id)->get();
        $leave_type=array();
        foreach($data as $rows){
            $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$users->id AND YEAR(created_at)='$date' LIMIT 1");
            if(!empty($select[0]->leave_type)){
                $total_leave = $rows->total_leave - $select[0]->leave_type;
            }else{
                $total_leave = $rows->total_leave;
            }
            $datas['name']=$rows->name;
            $datas['total_leave']=$total_leave;
            $leave_type[] = $datas;
        }
        return $leave_type;
    }
    public function mail()
    {
        return view('message');
    }


    


}
