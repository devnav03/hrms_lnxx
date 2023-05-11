<?php

namespace App\Http\Controllers\organization\letter_master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\LetterMaster;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use Illuminate\Support\Facades\Mail;
use DB;
class UserLetterMasterController extends Controller
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

    public function AddLetter(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($request->letter_name)){
            $letterMaster = new LetterMaster();
            $letterMaster->user_id = Auth::user()->id;
            $letterMaster->letter_name = $request->letter_name;
            $letterMaster->mode = $request->mode;
            $letterMaster->status = $request->status;
            $letterMaster->save();
            return redirect('add-letter')->with('success','Saved successfuly');
        }
        return view('organization.letter_master.add_letter',compact('organisation'));
    }

}