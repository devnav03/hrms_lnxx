<?php

namespace App\Http\Controllers\user\timesheet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Organisation;
use App\Models\ProjectMaster;
use App\Models\Timeseet;
use DB;
class UserTimesheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // public function GetOrganisation($user_id){
    //     $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
    //     return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    // }
    public function AddTimesheet(Request $request){
        $user = Auth::user();
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        $project = ProjectMaster::where(['user_id'=>$user->id,'orgnization_id'=>$organisation->user_id])->get();
        $emp_attendances = DB::select("SELECT TIMESTAMPDIFF(SECOND,in_time,TIME(now())) AS MinuteDiff FROM emp_attendances WHERE user_id=$user->id AND DATE(created_at)='$date'");
        if(!empty($request->project_id)){
            $timeseets = Timeseet::where(['id'=>$request->id])->first();
            if(!empty($timeseets)){
                $timeseets = new Timeseet();
                $timeseets->user_id = $user->id;
                $timeseets->orgnization_id = $organisation->user_id;
                $timeseets->project_id = $request->project_id;
                $timeseets->activity_id = $request->activity_id;
                $timeseets->start_time = $request->start_time;
                $timeseets->end_time = $request->end_time;
                $timeseets->duration = $request->duration;
                $timeseets->description = $request->description;
                $timeseets->status = $request->status;
                $timeseets->save();
                //$this->SendRegisterMail($request);
                return redirect('view-timesheet')->with('success','Updated successfuly');
            }else{
                $timeseets = new Timeseet();
                $timeseets->user_id = $user->id;
                $timeseets->orgnization_id = $organisation->user_id;
                $timeseets->project_id = $request->project_id;
                $timeseets->activity_id = $request->activity_id;
                $timeseets->start_time = $request->start_time;
                $timeseets->end_time = $request->end_time;
                $timeseets->duration = $request->duration;
                $timeseets->description = $request->description;
                $timeseets->status = $request->status;
                $timeseets->save();
                //$this->SendRegisterMail($request);
                return redirect('view-timesheet')->with('success','Saved successfuly');
            }
        }
        return view('user.timesheet.add_timesheet',compact('organisation','project','emp_attendances'));
    }
    public function ViewTimesheet(Request $request){
        $user = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        return view('user.timesheet.list',compact('organisation'));
    }
    
}
