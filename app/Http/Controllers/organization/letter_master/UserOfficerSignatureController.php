<?php

namespace App\Http\Controllers\organization\letter_master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\OfficerSignature;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use Illuminate\Support\Facades\Mail;
use DB;
class UserOfficerSignatureController extends Controller
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
        return Organisation::where(['user_id'=>$user_id])->first();
    }

    public function AddOfficerSignature(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($_POST)){
            $officerSignature = new OfficerSignature();
            $officerSignature->user_id = Auth::user()->id;
            $officerSignature->officer_name = $request->officer_name;

            if($request->hasFile('signature')){
                $filenames = strtolower($request->officer_name).'.'.$request->signature->extension();
                $request->signature->move(public_path('employee/signature'),$filenames);
                $officerSignature->signature = !empty($filenames) ? $filenames : '';
            }
            $officerSignature->save();
            return redirect('add-officer-signature')->with('success','Saved successfuly');
        }
        return view('organization.letter_master.add_officer_signature',compact('organisation'));
    }

}