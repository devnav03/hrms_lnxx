<?php

namespace App\Http\Controllers\user\letter_master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\LetterTemplate;
use App\Models\LetterMaster;
use App\Models\MapLetterTemplate;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use Illuminate\Support\Facades\Mail;
use DB;
class UserMapLetterTemplateController extends Controller
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

    public function AddMapLetterTemplate(Request $request){
        $user_id = Auth::user()->id;
        $letterMaster = LetterMaster::select('*')->where(['user_id'=>$user_id])->get();
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($_POST)){
            $mapLetterTemplate = new MapLetterTemplate();
            $mapLetterTemplate->user_id = Auth::user()->id;
            $mapLetterTemplate->letter_type = $request->letter_type;
            $mapLetterTemplate->letter_template = $request->letter_template;
            $mapLetterTemplate->authorised_officer = $request->authorised_officer;
            $mapLetterTemplate->save();
            return redirect('add-map-letter-template')->with('success','Saved successfuly');
        }
        return view('user.letter_master.add_map_letter_template',compact('organisation','letterMaster'));
    }

}