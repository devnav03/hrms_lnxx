<?php

namespace App\Http\Controllers\user\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpEducations;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
class UserEducationController extends Controller
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
    public function AddEducation(Request $request){
        $user_id = Auth::user()->id;
        $update=array();

        $upd = EmpEducations::where(['user_id'=>$user_id])->get();
        if(count($upd)>0){
            $update = $upd;
        }
        $organisation = $this->GetOrganisation($user_id);
        return view('user.employee.add_education',compact('organisation','update'));
    }

    public function UpdateEducation(Request $request){
        // print_r($_POST);die;
        $user_id = Auth::user()->id;
        $data=array();
        $count = count($request->education_type);
        if($request->education_type){
            for($i=0; $i<count($request->education_type); $i++){
                $educatoin = new EmpEducations();
                $educatoin->user_id = $user_id;
                $educatoin->education_type = $request->education_type[$i];
                $educatoin->course_name = $request->course_name[$i];
                $educatoin->board_university = $request->board_university[$i];
                $educatoin->percentage_cgpa = $request->percentage_cgpa[$i];
                $educatoin->from_year = $request->from_year[$i];
                $educatoin->to_year = $request->to_year[$i];
                if(!empty($request->document[$i])){
                $fileName2 = strtolower($request->course_name[$i]).'_'.$user_id.'_'.preg_replace('/\s\s+/', ' ', $request->course_name[$i]).'.'.$request->document[$i]->extension();
                $request->document[$i]->move(public_path('employee/education'),$fileName2);
                $educatoin->document = $fileName2;
                }
                $educatoin->save();
            }
           }
        return redirect()->back()->with('success', 'Update successfully');

    }

    public function DeleteEducation($id){
        EmpEducations::where('id',$id)->delete();
        return redirect('add-education')->with('success', 'Deleted successfully');  
    }
    
}
