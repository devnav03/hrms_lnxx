<?php

namespace App\Http\Controllers\user\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\EmpBank;
use App\Models\EmpDetail;
use App\Models\BankMaster;
use App\Models\Organisation;
class UserBankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function GetOrganisation($user_id){
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }
    public function AddBank(Request $request){
        $user_id = Auth::user()->id;
        $update = EmpBank::where(['user_id'=>$user_id])->first();
        $bank = BankMaster::orderBy('name', 'ASC')->get();
        $organisation = $this->GetOrganisation($user_id);
        return view('user.employee.add_bank',compact('organisation','update','bank'));
    }
    public function UpdateBank(Request $request){
        $user_id = Auth::user()->id;
        $select = EmpBank::where(['user_id'=>$user_id])->first();
        if(!empty($select)){
            $select->acc_holder_name = $request->acc_holder_name;
            $select->bank_id = $request->bank_id;
            $select->acc_number = $request->acc_number;
            $select->ifsc_code = $request->ifsc_code;
            $select->pan_number = $request->pan_number;
            $select->branch_name = $request->branch_name;
            $select->save();
            return redirect()->back()->with('success', 'Updated Successfully');   
        }else{
            $select = new EmpBank();
            $select->user_id = $user_id;
            $select->acc_holder_name = $request->acc_holder_name;
            $select->bank_id = $request->bank_id;
            $select->acc_number = $request->acc_number;
            $select->ifsc_code = $request->ifsc_code;
            $select->pan_number = $request->pan_number;
            $select->branch_name = $request->branch_name;
            $select->save();
            return redirect()->back()->with('success', 'Update Successfully');   
        }
    }
}
