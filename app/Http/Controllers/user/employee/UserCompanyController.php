<?php

namespace App\Http\Controllers\user\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpCompany;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
class UserCompanyController extends Controller
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
    public function AddCompany(Request $request){
        $user_id = Auth::user()->id;
        $update=array();
        $upd = EmpCompany::where(['user_id'=>$user_id])->get();
        if(count($upd)>0){
            $update = $upd;
        }
        $organisation = $this->GetOrganisation($user_id);
        return view('user.employee.add_company_details',compact('organisation','update'));
    }
    public function UpdateCompany(Request $request){
        $user_id = Auth::user()->id;
        $count = count($request->comp_name);
        for($i=0;$i<$count;$i++){
            $select = EmpCompany::where('comp_name',$request->comp_name[$i])->first();
            if(empty($select)){
                $empcompany = new EmpCompany();
                $empcompany->user_id = $user_id;
                $empcompany->comp_name = $request->comp_name[$i];
                $empcompany->designation = $request->designation[$i];
                $empcompany->date_of_joining = $request->date_of_joining[$i];
                $empcompany->date_of_resignation = $request->date_of_resignation[$i];
                $empcompany->ctc = $request->ctc[$i];
                $empcompany->reason_for_leav_comp = $request->reason_for_leav_comp[$i];
                $empcompany->save();
            }
        }
        return redirect()->back()->with('success', 'Update successfully');   
    }
    public function DeleteEmpCompany($id){
        EmpCompany::where('id',$id)->delete();
        return redirect('add-company')->with('success', 'Deleted successfully');  
    }
}
