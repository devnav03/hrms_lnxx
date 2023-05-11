<?php

namespace App\Http\Controllers;
/**
 * :: Notification Controller ::
 * 
 *
 **/

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\Organisation;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\PositionMaster;
use Illuminate\Http\Request;

class NotificationController extends  Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $organisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
        $result = \DB::table('notifications')
                        ->join('users', 'notifications.created_by', '=', 'users.id')
                        ->select('notifications.master_id', 'notifications.notication_type', 'notifications.title', 'notifications.status', 'notifications.id', 'notifications.created_at', 'users.name as user_name', 'notifications.employee_id')
                        ->orderBy('notifications.id', 'desc')
                        ->where('notifications.crone_status', 0)
                        ->get(); 


        return view('organization.notification.index', compact('organisation', 'result'));
    }

    public function notification_status(Request $request){

        Notification::where('id', $request->id)->update(['status'  =>  $request->status ]);
        $responce['status']=200; 
        echo json_encode($responce);
    }

    public function notification_history(){
        $organisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
        
        $result = Notification::where('crone_status', 1)->select('id', 'notication_type', 'employee_id', 'master_id', 'title', 'image', 'description')->get();

        return view('organization.notification.notification_history', compact('organisation', 'result'));
    }

    public function notification_reports($id){

    try {
     
        $result = \DB::table('push_notification_histories')
                        ->join('users', 'users.id', '=', 'push_notification_histories.user_id')
                        ->join('employee_infos', 'employee_infos.user_id', '=', 'users.id')
                        ->select('users.name', 'employee_infos.employee_code', 'push_notification_histories.status', 
                            'push_notification_histories.notification_id', 'users.mobile')
                        ->where('employee_infos.employee_code', '!=', NULL)
                        ->where('push_notification_histories.notification_id', $id)->get(); 

        $content = view('organization.notification.reports', compact('result'));
        
        $status = 200;
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="notification_reports.xls"',
        ];

        $response = response($content, $status, $headers);
        return $response;
        }
        catch (Exception $exception) {
           // dd($exception);
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }

    }

    public function notication_type(Request $request){
        //dd($request);
        $user_id = Auth::user()->id; 
        if($request->notication_id ==1){
            $datas = OfficeMaster::where('status', 'Active')->where('orgnization_id', $user_id)->select('office_name', 'id')->get();
            if(!empty($datas)){
               foreach($datas as $row){
                   $data['id']=$row->id;
                   $data['name']=$row->office_name;
                   $dt[]=$data;
               }
               $responce['alldata']=$dt;
               $responce['names']='Select Office';
               $responce['multiple']=0;
               $responce['status']=200;
            }
        }

        if($request->notication_id ==2){
            $datas = DepartmentMaster::where('status', 'Active')->where('orgnization_id', $user_id)->select('department_name', 'id')->get();
            if(!empty($datas)){
                foreach($datas as $row){
                   $data['id']=$row->id;
                   $data['name']=$row->department_name;
                   $dt[]=$data;
                }
               $responce['alldata']=$dt;
               $responce['names']='Select Department';
               $responce['multiple']=0;
               $responce['status']=200;
            }
        }

        if($request->notication_id ==3){
            $datas = PositionMaster::where('status', 'Active')->where('orgnization_id', $user_id)->select('position_name', 'id')->get();
            if(!empty($datas)){
                foreach($datas as $row){
                   $data['id']=$row->id;
                   $data['name']=$row->position_name;
                   $dt[]=$data;
                }
               $responce['alldata']=$dt;
               $responce['names']='Select Designation';
               $responce['multiple']=0;
               $responce['status']=200;
            }
        }


        if($request->notication_id ==4){
            $responce['names']='Enter Specific Employees';
            $responce['status']=202;
        }

        echo json_encode($responce);
    }
  
    public function create() {
     
        return view('organization.notification.create');
    }

    public function store(Request $request) {
        $inputs = $request->all();
       // dd($request);
        try {
            $user_id = Auth::user()->id;
            if(isset($inputs['image']) or !empty($inputs['image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('image')) {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/notification/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/notification/';
                $image = $fname.$fileName;
            }  else{
                $image = '';
            }
            
            unset($inputs['image']);
            $inputs['image'] = $image;

            // $id = (new Notification)->store($inputs);
            if($request->notication_type == 4){
                Notification::create([
                    'image' => $image,
                    'notication_type' => $request->notication_type,
                    'title' => $request->title,
                    'description' => $request->description,
                    'employee_id' => $request->datatypes2,
                    'created_by' => $user_id,
                ]);
            } else {
                Notification::create([
                    'image' => $image,
                    'notication_type' => $request->notication_type,
                    'title' => $request->title,
                    'description' => $request->description,
                    'master_id' => $request->datatypes,
                    'created_by' => $user_id,
                ]);
            }
            
            return back()->with('notification_created', 'notification_created');

            // return redirect()->route('notification.index')
            //     ->with('success', 'Notification successfully created');
        } catch (\Exception $exception) {
            dd($exception);
            return back();
        }
    }

  
    public function update(Request $request, $id = null) {
        $result = (new Notification)->find($id);
        if (!$result) {
            abort(401);
        }
        $inputs = $request->all();
        try {
          
            if(isset($inputs['image']) or !empty($inputs['image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('image')) {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/notification/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/notification/';
                $image = $fname.$fileName;
            } else{
                $image = $result->image;
            }
            unset($inputs['image']);
            $inputs['image'] = $image;
            (new Notification)->store($inputs, $id);

            return redirect()->route('notification.index')
                ->with('success', 'Notification successfully updated');

        } catch (\Exception $exception) {
            return redirect()->route('notification.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

  
    public function edit($id = null) {
        $result = (new Notification)->find($id);
        if (!$result) {
            abort(401);
        }
 
        return view('organization.notification.create', compact('result'));
    }


    public function Paginate(Request $request, $pageNumber = null) {
     // dd($request);

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

       // dd('test');

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);
            $data = (new Notification)->getNotification($inputs, $start, $perPage);
            $totalGameMaster = (new Notification)->totalNotification($inputs);
            $total = $totalGameMaster->total;
            // dd($data);
        } else {
            $data = (new Notification)->getNotification($inputs, $start, $perPage);
            $totalGameMaster = (new Notification)->totalNotification();
            $total = $totalGameMaster->total;
        }

       // dd($data);

        return view('organization.notification.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

 
    public function Toggle($id = null) {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }
        try {
            $game = Notification::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Notification')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

  
    public function Action(Request $request)  {

        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('notification.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Notification'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        Notification::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('notification.index')
            ->with('success', lang('messages.updated', lang('Notification')));
    }


    public function drop($id) {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new Notification)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }
        try {
            // get the unit w.r.t id
            $result = (new Notification)->find($id);
            // if($result->status == 1) {
            //     $response = ['status' => 0, 'message' => lang('category.category_in_use')];
            // }
            //  else {
                (new Notification)->tempDelete($id);
                $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Notification'))];
             // }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    
}
