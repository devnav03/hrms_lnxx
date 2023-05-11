<?php

namespace App\Http\Controllers\user\attendance;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\EmpDetail;
use App\Models\Organisation;
use DB;
class AttendanceController extends Controller
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
    public function AttendanceDetails(){
        $user = Auth::user();
        $date = date('Y-m-d');
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        $attend = DB::select("SELECT count(*) as aggregate from `emp_attendances` where `user_id` = $user->id and `out_time` IS NOT NULL and `in_time` IS NOT NULL and DATE(created_at) = '$date'");
        if($attend[0]->aggregate > 0){
            $attendance = true;
        } else {
            $attendance = false;
        }

        return view('user.attendance.list',compact('organisation','attendance'));
    }
}
