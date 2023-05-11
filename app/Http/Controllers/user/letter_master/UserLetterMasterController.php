<?php

namespace App\Http\Controllers\user\letter_master;
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
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }

    public function AddLetter(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($_POST)){
            $letterMaster = new LetterMaster();
            $letterMaster->user_id = Auth::user()->id;
            $letterMaster->letter_name = $request->letter_name;
            $letterMaster->mode = $request->mode;
            $letterMaster->status = $request->status;
            $letterMaster->save();
            return redirect('add-letter')->with('success','Saved successfuly');
        }
        return view('user.letter_master.add_letter',compact('organisation'));
    }

}