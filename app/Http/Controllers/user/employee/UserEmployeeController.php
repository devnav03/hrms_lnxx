<?php

namespace App\Http\Controllers\user\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;
class UserEmployeeController extends Controller
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
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }

    public function AddEmployee(Request $request){
        $user = Auth::user()->id;
        $user_email = Auth::user()->email;
        $update = EmpDetail::where(['user_id'=>$user->id])->first();
        $organisation = $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
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
}