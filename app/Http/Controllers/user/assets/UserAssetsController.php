<?php

namespace App\Http\Controllers\user\assets;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\AssetsTypes;
use App\Models\AssetsRequests;
use App\Models\Organisation;
use DB;
class UserAssetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function AssetsRequest(Request $request){
        $user = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        $assets_name = AssetsTypes::where('orgnization_id',$organisation->user_id)->OrderBy('assets_name','ASC')->get();

        if($request->assets_type){
            $AssetsName = AssetsRequests::select('id')->where('assets_type', $request->assets_type)->where('user_id',$organisation->user_id)->first();
            if(empty($AssetsName->id)){
                $assetsRequests = new AssetsRequests();
                $assetsRequests->user_id = $user->id;
                $assetsRequests->assets_type = $request->assets_type;
                $assetsRequests->start_date = $request->start_date;
                $assetsRequests->end_date = $request->end_date;
                $assetsRequests->description = $request->description;
                $assetsRequests->save();
                return redirect('assets-request')->with('success','Saved successfuly');
            }else{
                return redirect('assets-request')->with('error','Assets Name Already Exist');
            }
        }
        return view('user.assets.add_assets_request',compact('organisation','assets_name'));
    }
}
