<?php

namespace App\Http\Controllers\user\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\EmpContact;
use App\Models\EmpDetail;
use App\Models\State;
use App\Models\City;
use App\Models\Organisation;
class UserContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function GetOrganisation($user_id){
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }
    public function AddContact(Request $request){
        $state = State::select(['id','name'])->orderBy('name', 'ASC')->get();
        $city = City::select(['id','name'])->orderBy('name', 'ASC')->get();
        $user_id = Auth::user()->id;
        $update = EmpContact::where(['user_id'=>$user_id])->first();
        $organisation = $this->GetOrganisation($user_id);
        return view('user.employee.add_contact',compact('organisation','update','state','city'));
    }
    public function UpdateContact(Request $request){
        $user_id = Auth::user()->id;
        $select = EmpContact::where(['user_id'=>$user_id])->first();
        if(!empty($select)){
            $select->mobile = $request->mobile;
            $select->father_mobile = $request->father_mobile;
            $select->friend_mobile = $request->friend_mobile;
            $select->state_id = $request->state_id;
            $select->city_id = $request->city_id;
            $select->address = $request->address;
            $select->pincode = $request->pincode;
            $select->save();
            return redirect()->back()->with('success', 'Saved Successfully');   
        }else{
            $select = new EmpContact();
            $select->user_id = $user_id;
            $select->mobile = $request->mobile;
            $select->father_mobile = $request->father_mobile;
            $select->friend_mobile = $request->friend_mobile;
            $select->state_id = $request->state_id;
            $select->city_id = $request->city_id;
            $select->address = $request->address;
            $select->pincode = $request->pincode;
            $select->save();
            return redirect()->back()->with('success', 'Update Successfully');   
        }
    }
}
