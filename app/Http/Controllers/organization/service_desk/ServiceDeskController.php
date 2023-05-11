<?php

namespace App\Http\Controllers\organization\service_desk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Organisation;
use DB;
class ServiceDeskController extends Controller
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
    
    public function helpManual(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.service_desk.help_manual',compact('organisation'));
    }
    public function accessToVariouseForm(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.service_desk.access_to_variouse_form',compact('organisation'));
    }
    public function lodgingTrackingSuggetions(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.service_desk.lodging_tracking_suggetions',compact('organisation'));
    }
    public function suggetionsManagement(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.service_desk.suggetions_management',compact('organisation'));
    }
    
}
