<?php

namespace App\Http\Controllers\organization\project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\Organisation;
use App\Models\EmpProject;
use DB;
class ProjectController extends Controller
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
    public function AddEmpProject(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $emp_details = DB::select("SELECT a.user_id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `emp_details` as a  INNER JOIN users as b on b.id=a.user_id WHERE a.created_by=$user_id AND b.type=2 GROUP by a.id ORDER BY name ASC");
        $project_details = DB::select("SELECT id,project_name,orgnization_id FROM project_masters WHERE orgnization_id=$user_id");
        if(!empty($_POST)){
            $empProject = new EmpProject();
            $empProject->orgnization_id = $user_id;
            $empProject->project_id = $request->project_id;
            $empProject->employee_id = $request->employee_id;
            $empProject->start_date = $request->start_date;
            $empProject->end_date = $request->end_date;
            $empProject->description = $request->description;
            $empProject->save();
            return redirect('add-emp-assign-project')->with('success','Saved successfuly');
        }

        return view('organization.project.add_amp_assign_project',compact('organisation','emp_details','project_details'));
    }
    public function ViewProjectDetails(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $emp_details = DB::select("SELECT a.user_id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `emp_details` as a  INNER JOIN users as b on b.id=a.user_id WHERE a.created_by=$user_id AND b.type=2 GROUP by a.id ORDER BY name ASC");
        return view('organization.project.view_project_details',compact('organisation','emp_details'));
    }
    
}
