<?php

namespace App\Http\Controllers\organization\timesheet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\Organisation;
use DB;
class TimesheetController extends Controller
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

    public function GetOrganisation($user_id){
        return Organisation::where(['user_id'=>$user_id])->first();
    }
    public function ViewEmployeeTimesheet(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);

        // $timesheet_details = DB::select("SELECT a.id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `users` as a LEFT JOIN timeseets as b on a.id=b.user_id WHERE a.created_by=$user_id  GROUP by a.id ORDER BY name ASC;");
        

        $timesheet_details = \DB::table('timeseets')
            ->join('users', 'users.id', '=', 'timeseets.user_id')
            ->select('users.name', 'users.id')
            ->orderBy('timeseets.id', 'desc')
            ->where('timeseets.orgnization_id', $user_id)->groupBy('users.id')->get(); 

        return view('organization.timesheet.view_employee_timesheetlist',compact('organisation','timesheet_details'));
    }
    
}
