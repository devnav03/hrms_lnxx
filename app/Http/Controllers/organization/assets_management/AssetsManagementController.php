<?php

namespace App\Http\Controllers\organization\assets_management;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Organisation;
use App\Models\SalaryHeadMaster;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\AssetsRequests;
use App\Models\PositionMaster;
use App\Models\AvaliableAsset;
use DB;
class AssetsManagementController extends Controller
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
    
    public function assetsPendingRequest(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        
        $result = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,c.name,e.employee_code,c.email,c.mobile,f.office_name,a.status,a.created_at FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id INNER JOIN users AS c ON a.user_id=c.id INNER JOIN employee_infos as e on e.user_id=a.user_id INNER JOIN office_masters as f on f.id=e.office_id WHERE e.employee_code is not null AND a.status IN ('Pending','Reject') ORDER BY a.id DESC");
        return view('organization.assets_management.assets_pending_request',compact('organisation','result'));
    }
    public function updateAssetsStatus(Request $request){
        //dd($request->all());
        $user_id = Auth::user()->id;
        
        if(!empty($request->id)){
            $id = $request->id;
        }else{
            $id = $request->segment(2);
        }
        $avaliable_asset = AvaliableAsset::where(['orgnization_id'=>$user_id])->get();
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $result = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,c.name,e.employee_code,c.email,c.mobile,f.office_name,a.status,a.created_at FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id INNER JOIN users AS c ON a.user_id=c.id INNER JOIN employee_infos as e on e.user_id=a.user_id INNER JOIN office_masters as f on f.id=e.office_id WHERE a.id=$id AND e.employee_code is not null ORDER BY a.id DESC");
        if(!empty($result[0])){
            $assets = $result[0];
            if(!empty($_POST)){
                $assets_requests = AssetsRequests::where('id',$request->id)->first();
                $assets_requests->status = $request->chng_status;
                $assets_requests->description_admin = $request->admin_description;
                $assets_requests->avalible_assets = $request->avaliable_assets;
                $assets_requests->start_date_admin = $request->app_from_date;
                $assets_requests->end_date_admin = $request->app_to_date;
                $assets_requests->save();
                return redirect('assets-pending-request')->with('success','Updated Successfully');
            }
            return view('organization.assets_management.update_assets_status',compact('organisation','assets','avaliable_asset'));
        }else{
            return redirect('assets-pending-request')->with('error','No data found');
        }
    }
    public function returnAssetsReportStatus(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.return_assets_status',compact('organisation'));
    }
    public function assetsReport(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $result = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,c.name,e.employee_code,c.email,c.mobile,f.office_name,a.status,a.created_at FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id INNER JOIN users AS c ON a.user_id=c.id INNER JOIN employee_infos as e on e.user_id=a.user_id INNER JOIN office_masters as f on f.id=e.office_id WHERE e.employee_code is not null AND a.status='Approve' ORDER BY a.id DESC");
        return view('organization.assets_management.assets_report',compact('organisation','result'));
    }
    public function addAssetsItem(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.add_assets_item',compact('organisation'));
    }
    public function addComponent(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.add_component',compact('organisation'));
    }
    public function viewAssetsItem(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.view_assets_item',compact('organisation'));
    }
    public function assetsType(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        return view('organization.assets_management.assets_type',compact('organisation','office'));
    }
    public function assetsBrand(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.assets_brand',compact('organisation'));
    }
    public function assetsOurVendor(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $department=[];
        if(!empty($request->segment(2))){
            $department = DepartmentMaster::where('office_id',$request->office_id)->get();
        }

        return view('organization.assets_management.assets_our_vendor',compact('organisation','office','department'));
    }
    public function assetsInwardOutward(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.assets_inward_outward',compact('organisation'));
    }
    public function outwardAssetsList(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.outward_assets_list',compact('organisation'));
    }
    public function inwardAssetsList(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        return view('organization.assets_management.inward_assets_list',compact('organisation'));
    }
    
}
