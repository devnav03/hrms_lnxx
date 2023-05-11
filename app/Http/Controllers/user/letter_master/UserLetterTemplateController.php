<?php

namespace App\Http\Controllers\user\letter_master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\LetterTemplate;
use App\Models\LetterMaster;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use Illuminate\Support\Facades\Mail;
use DB;
class UserLetterTemplateController extends Controller
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

    public function AddLetterTemplate(Request $request){
        $user_id = Auth::user()->id;
        $letterMaster = LetterMaster::select('*')->where(['user_id'=>$user_id])->get();
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($_POST)){
            $letterTemplate = new LetterTemplate();
            $letterTemplate->user_id = Auth::user()->id;
            $letterTemplate->letter_type = $request->letter_type;
            $letterTemplate->language = $request->language;
            $letterTemplate->status = $request->status;
            $letterTemplate->variable = $request->variable;
            $letterTemplate->description = $request->description;
            $letterTemplate->save();
            return redirect('add-letter-template')->with('success','Saved successfuly');
        }
        return view('user.letter_master.add_letter_template',compact('organisation','letterMaster'));
    }

}