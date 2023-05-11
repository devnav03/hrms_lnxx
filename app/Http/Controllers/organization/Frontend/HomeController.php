<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserOtp;
use App\Models\User;
use App\Models\EmployeeInfo;
use App\Models\AttendanceHistory;
use App\Models\EmpAttendance;
use App\Models\ShiftDuration;
use App\Models\LeaveApprovalSent;
use App\Models\Leave;
use App\Models\Organisation;
use App\Models\Notification;
use App\Models\PushNotificationHistory;
use App\Models\SendOfferLettersToCandidate;
use App\Models\SendHrRequest;
use App\Models\PositionMaster;
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use DB;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Mail;
//use thiagoalessio\TesseractOCR\TesseractOCR;

class HomeController extends Controller {
   
    public function index() {
        
       echo "Welcome To HRMS"; exit;
        
    }

     
    public function push_notification_crone(){

        $notification = Notification::where('status', 1)->where('crone_status', 0)->get();
        $home = route('get-started');

        if(count($notification) != 0){
            foreach ($notification as $notifi) {

                $title = $notifi->title;
                $description = $notifi->description;
                $image = $home.$notifi->image;
                $users = [];

                if($notifi->notication_type == 1){
                    $users = \DB::table('users')
                        ->join('employee_infos', 'employee_infos.user_id', '=', 'users.id')
                        ->select('users.id', 'users.fcm_id')
                        ->where('employee_infos.employee_code', '!=', NULL)
                        ->where('users.status', 'Active')
                        ->where('employee_infos.office_id', $notifi->master_id)
                        ->get(); 
                }

                if($notifi->notication_type == 2){
                    $users = \DB::table('users')
                        ->join('employee_infos', 'employee_infos.user_id', '=', 'users.id')
                        ->select('users.id', 'users.fcm_id')
                        ->where('employee_infos.employee_code', '!=', NULL)
                        ->where('users.status', 'Active')
                        ->where('employee_infos.department_id', $notifi->master_id)
                        ->get(); 
                }

                if($notifi->notication_type == 3){
                    $users = \DB::table('users')
                        ->join('employee_infos', 'employee_infos.user_id', '=', 'users.id')
                        ->select('users.id', 'users.fcm_id')
                        ->where('employee_infos.employee_code', '!=', NULL)
                        ->where('users.status', 'Active')
                        ->where('employee_infos.position_id', $notifi->master_id)
                        ->get(); 
                }

                if($notifi->notication_type == 4){
                    $variable=explode(",", $notifi->employee_id);
                    $users = \DB::table('users')
                        ->join('employee_infos', 'employee_infos.user_id', '=', 'users.id')
                        ->select('users.id', 'users.fcm_id')
                        ->whereIn('employee_infos.employee_code', $variable)
                        ->where('users.status', 'Active')
                        ->get();      
                }

                if(count($users) != 0){
                    foreach($users as $user) {
                        $device_token = $user->fcm_id;
                        $push_noti = $this->sendGCM($title,$description,$image,$device_token);
                        $push_noti = json_decode($push_noti);
                        $success = $push_noti->success;
                        if($success == 1){
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                            $PushNotification = new PushNotificationHistory();
                            $PushNotification->user_id = $user->id;
                            $PushNotification->notification_id = $notifi->id;
                            $PushNotification->status = $status;
                            $PushNotification->save();
                    }
                }

                Notification::where('id', $notifi->id)->update([
                    'crone_status' => 1,
                ]);
            }
        }
    }

    public function areyousure_offer_letterreject($id = null){
        try{

            $token = \decrypt($id); 
            $offer = SendOfferLettersToCandidate::where('id', $token)->select('status', 
                'organisation_id')->first();
            if(empty($offer)){
                echo "Something went wrong"; exit;
            } else { 

                $org = Organisation::where('user_id', $offer->organisation_id)->select('logo')->first();

                return view('user.employee.letterreject', compact('id', 'org'));
            }
        } catch (Exception $ex) {
            return false;
        } 
    }

    public function areyousure_offer_letteraccept($id = null){
        try{

            $token = \decrypt($id); 
            $offer = SendOfferLettersToCandidate::where('id', $token)->select('status', 
                'organisation_id')->first();
            if(empty($offer)){
                echo "Something went wrong"; exit;
            } else { 

                $org = Organisation::where('user_id', $offer->organisation_id)->select('logo')->first();

                return view('user.employee.letteraccept', compact('id', 'org'));
            }
        } catch (Exception $ex) {
            return false;
        } 
    }
    
    public function Send_offer_letter_accept_Mail($data, $email){
        try {
            $template_data = [
                'email' => $email,
                'name' => $data->name,
                'position'=>$data->position,
            ];
            Mail::send(['html'=>'email.offer_letter_accept'], $template_data,
                function ($message) use ($data) {
                    $message->to($data->email)->from('lnxxapp@gmail.com')->subject('Offer Letter Accepted');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        } 
    }

    public function Send_offer_letter_reject_Mail($data, $email){
        try {
            $template_data = [
                'email' => $email,
                'name' => $data->name,
                'position'=>$data->position,
            ];
            Mail::send(['html'=>'email.offer_letter_reject'], $template_data,
                function ($message) use ($data) {
                    $message->to($data->email)->from('lnxxapp@gmail.com')->subject('Offer Letter Accepted');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        } 
    }

    public function offer_letter_accept($id = null){
        $id = \decrypt($id); 
        $offer = SendOfferLettersToCandidate::where('id', $id)->select('status', 'candidate_id')->first();
        if(empty($offer)){
            echo "Something went wrong"; exit;
        } else {
        if($offer->status == 0){

            $SendOfferLettersToCandidate = SendHrRequest::where('id', $offer->candidate_id)->first();
            $SendOfferLettersToCandidate->hiring_status = 2;
            $SendOfferLettersToCandidate->save();

            $SendOfferLettersToCandidate = SendOfferLettersToCandidate::where('id', $id)->first();
            $SendOfferLettersToCandidate->status = 1;
            $SendOfferLettersToCandidate->save();

            $send_hr = SendHrRequest::where('id', $offer->candidate_id)->select('candidate_position_id', 'hr_email', 'candidate_name')->first();
            $position = PositionMaster::where('id', $send_hr->candidate_position_id)->select('position_name')->first();
            $email = $send_hr->hr_email;
            $reponce=(object)[
                'name'      => $send_hr->candidate_name,
                'position'  => $position->position_name,
                'email'  => $email,
            ];

            $this->Send_offer_letter_accept_Mail($reponce, $email);

            echo "Offer Letter is successfully accepted"; exit;
        }
            if($offer->status == 1){
                echo "Offer Letter is already accepted"; exit;
            }
            if($offer->status == 2){
                echo "Offer Letter is already rejected"; exit;
            }
        }
    }

    public function offer_letter_reject($id = null){
        $id = \decrypt($id); 
        $offer = SendOfferLettersToCandidate::where('id', $id)->select('status', 'candidate_id')->first();
        if(empty($offer)){
            echo "Something went wrong"; exit;
        } else {
           if($offer->status == 0){

            $SendOfferLettersToCandidate = SendHrRequest::where('id', $offer->candidate_id)->first();
            $SendOfferLettersToCandidate->hiring_status = 3;
            $SendOfferLettersToCandidate->save();

            $SendOfferLettersToCandidate = SendOfferLettersToCandidate::where('id', $id)->first();
            $SendOfferLettersToCandidate->status = 2;
            $SendOfferLettersToCandidate->save();

            $send_hr = SendHrRequest::where('id', $offer->candidate_id)->select('candidate_position_id', 'hr_email', 'candidate_name')->first();
            $position = PositionMaster::where('id', $send_hr->candidate_position_id)->select('position_name')->first();
            $email = $send_hr->hr_email;
            $reponce=(object)[
                'name'      => $send_hr->candidate_name,
                'position'  => $position->position_name,
                'email'  => $email,
            ];

            

            $this->Send_offer_letter_reject_Mail($reponce, $email);
 

              echo "Offer Letter is successfully rejected"; exit;
           }
           if($offer->status == 1){
                echo "Offer Letter is already accepted"; exit;
            }
            if($offer->status == 2){
                echo "Offer Letter is already rejected"; exit;
            }

        }
    }
    function sendGCM($title,$description,$image,$id) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
                'registration_ids' => array (
                        $id
                ),
                'notification' => array(
                    "body" => $description,
                    "title" => $title,
                    "image"  => $image,
            )
        );
        $fields = json_encode($fields);
        $headers = array (
            'Authorization: key=' . "AAAAf-mii9A:APA91bHS90QW0B-UVMS7N6UDJ0ODFIaZJLSmiTOgZB3sIsid_5ubyEOgVidf2PVoh27eho3NLb6fRfqh0tC6DGJl13MNVMeauOf7lRW7344A8gr-5OjvwDtZ32lr3Frz3HafCWLHLFJz",
            'Content-Type: application/json'
        );

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        curl_close ($ch);
        return $result;
    }  

     public function leave_reject($token){
        $id = \decrypt($token);
        $leave = LeaveApprovalSent::where('id', $id)->first();
        if(empty($leave)){
            echo "Something went wrong"; exit;
        } else {
            if($leave->status == 0){
            $user_id = $leave->user_id;
            $leaves = Leave::where('id',$leave->leave_id)->first();
            $leaves->status = 'Reject';
            $leaves->updated_by = $user_id;
            $leaves->save();

            $LeaveApprovalSent = LeaveApprovalSent::where('id', $id)->first();
            $LeaveApprovalSent->status = 1;
            $LeaveApprovalSent->save();

            $data = User::select('name','email')->where('id',$leaves->user_id)->first();
            $canceled_emp = User::select('name')->where('id',$user_id)->first();
            $reponce=(object)[
                'name'              =>$data->name,
                'email'             =>$data->email,
                'status'            =>'Reject',
                'canceled_emp'      =>$canceled_emp->name,
                'from'              =>date_format(date_create($leaves->start_date),"d-M-Y"),
                'to'                =>date_format(date_create($leaves->end_date),"d-M-Y"),
            ];
            $this->SendRegisterMail($reponce, $user_id);
                echo "Leave successfully rejected"; exit;
            }
            if($leave->status == 1){
                echo "Leave is already approved"; exit;
            }
            if($leave->status == 2){
                echo "Leave is already rejected"; exit;
            }
        }
    }

     public function leave_approve($token){
        $id = \decrypt($token);
        $leave = LeaveApprovalSent::where('id', $id)->first();
        if(empty($leave)){
            echo "Something went wrong"; exit;
        } else {
            if($leave->status == 0){
            $user_id = $leave->user_id;
            $leaves = Leave::where('id',$leave->leave_id)->first();
            $leaves->status = 'Approved';
            $leaves->updated_by = $user_id;
            $leaves->save();

            $LeaveApprovalSent = LeaveApprovalSent::where('id', $id)->first();
            $LeaveApprovalSent->status = 1;
            $LeaveApprovalSent->save();

            $data = User::select('name','email')->where('id',$leaves->user_id)->first();
            $canceled_emp = User::select('name')->where('id',$user_id)->first();
            $reponce=(object)[
                'name'              =>$data->name,
                'email'             =>$data->email,
                'status'            =>'Approved',
                'canceled_emp'      =>$canceled_emp->name,
                'from'              =>date_format(date_create($leaves->start_date),"d-M-Y"),
                'to'                =>date_format(date_create($leaves->end_date),"d-M-Y"),
            ];
            $this->SendRegisterMail($reponce, $user_id);
                echo "Leave successfully approved"; exit;
            }
            if($leave->status == 1){
                echo "Leave is already approved"; exit;
            }
            if($leave->status == 2){
                echo "Leave is already rejected"; exit;
            }
        }
    }
    
    public function SendRegisterMail($data, $user_id){
        try {
            $user = User::where('id', $user_id)->select('organisation_id')->first();
            $orgnisation = Organisation::where('user_id', $user->organisation_id)->first();
            $template_data = [
                'email' => $data->email,
                'name' => $data->name,
                'canceled_emp'=>$data->canceled_emp,
                'status'=>$data->status,
                'from'=>$data->from,
                'to'=>$data->to,
                'user_name'=>$orgnisation->user_name,
            ];
            \Mail::send(['html'=>'email.leave'], $template_data,
                function ($message) use ($data) {
                    $message->to($data->email)->from('lnxxapp@gmail.com')->subject('Leave '.$data->status);
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function reportSync(Request $request){
       
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $page = 1;
        $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$page);
     
        $record=[];
        if(isset($all_reports['total_pages']) && $all_reports['total_pages'] > 0){
            $total_page=$all_reports['total_pages'];
            for($i=1; $i <= $total_page; $i++){
                $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$i);
                if(!empty($all_reports['data'])){
                    foreach($all_reports['data'] as $data){
                        $data['update_in_type']='ams';
                        $data['update_out_type']='ams';
                        array_push($record,$data);

                        // if (strpos($data['employee_id'], 'vs') !== false) {                         
                        //     $data['update_in_type']='ams';
                        //     $data['update_out_type']='ams';
                        //     array_push($record,$data);
                        // }
                        // if (strpos($data['employee_id'], 'vms') !== false) {                            
                        //     $data['update_in_type']='ams';
                        //     $data['update_out_type']='ams';
                        //     array_push($record,$data);
                        // }
                    }
                }
                
            }
        }
    
        // $cidata_ob= 'CID33';
        $VisitorHistory_data= AttendanceHistory::where(['last_synchronize_date'=>$start_date])->first();
     
        if(!isset($VisitorHistory_data->last_synchronize_date)){
            
            $VisitorHistory=new AttendanceHistory();
            // $VisitorHistory->company_id = $cidata_ob;
            $VisitorHistory->ams_data=json_encode($record);
            $VisitorHistory->last_synchronize_date=$start_date;
            $VisitorHistory->save();         
            
        }else{
            $new_record=[];
            $all_data = json_decode($VisitorHistory_data->ams_data);
            $visitor_ids=array_column((array)$all_data,'employee_id');
            $delete_employee=[];

            foreach($record as $key => $data ){
                if(in_array($data['employee_id'],$visitor_ids)){

                    $user_visitor = EmployeeInfo::where('employee_code', $data['employee_id'])->select('user_id as id')->first();

                    if(!empty($user_visitor)){
                    $date = date('Y-m-d');
                    
                  //  dd($user_visitor->id);
                    $all_visit_update = EmpAttendance::where('user_id', $user_visitor->id)->whereRaw('date_format(created_at,"%Y-%m-%d")'."='".$date . "'")->first();
                    $date = date('d-m-Y');
                    $in_time = '';
                    $out_time = '';
                    

                    if(@$data['in_time']){
                    
                    $in_time = str_replace($date,"",@$data['in_time']);
                    $in_time = str_replace("am",":00",@$in_time);
                    $in_time = str_replace("pm",":00",@$in_time);
                    $in_time = str_replace(" ","",@$in_time);

                    if(@$data['out_time']){
                        $out_time = str_replace($date,"",@$data['out_time']);
                        $out_time = str_replace("am",":00",@$out_time);
                        $out_time = str_replace("pm",":00",@$out_time);
                        $out_time = str_replace(" ","",@$out_time);
                    }


                    if($all_visit_update){
                        // $all_visit_update->in_time = $in_time;
                        // $all_visit_update->in_device = @$data['in_device'];
                        // $all_visit_update->in_status = @$data['in_time']?'Yes':'';
                        // $date = date('Y-m-d');
                        // $curren_time = date('H:i:s');

                        // $attendance = DB::select("SELECT id,TIMEDIFF('$curren_time',in_time) as totaltime from `emp_attendances` WHERE DATE(created_at) = '$date' AND user_id=$user_visitor->id LIMIT 1");
                        $seconds = @$data['actual_work_time']*60;
                        $H = floor($seconds / 3600);
                        $i = ($seconds / 60) % 60;
                        $s = $seconds % 60;
                        $totaltime = sprintf("%02d:%02d:%02d", $H, $i, $s);
                        $all_visit_update->out_device = @$data['out_device'];
                        $all_visit_update->out_time = $out_time;
                        $all_visit_update->out_image = @$data['out_time_image'];
                        $all_visit_update->total_time = $totaltime;
                        $all_visit_update->out_status = @$data['out_time']?@'Yes':'';
                        $all_visit_update->save();

                    } else {
                        
                        $user = User::where('id', $user_visitor->id)->select('shift_id')->first();
                        $shift = ShiftDuration::where('shift_id', @$user->shift_id)->select('in_time_relaxation', 'out_time_relaxation')->first();

                        $all_visit_update =  (new EmpAttendance);
                        $all_visit_update->user_id = $user_visitor->id;
                        $all_visit_update->in_time = $in_time;
                        $all_visit_update->in_device = @$data['in_device'];
                        $all_visit_update->in_status = @$data['in_time']?'Yes':'';
                        $all_visit_update->out_device = @$data['out_device'];
                        $all_visit_update->out_time =  $out_time;
                        $all_visit_update->in_image = @$data['in_time_image'];
                        $all_visit_update->out_status = @$data['out_time']?@'Yes':'';
                        $all_visit_update->start_date = @$shift->in_time_relaxation;  
                        $all_visit_update->end_date = @$shift->out_time_relaxation; 
                        $all_visit_update->save();

                    }
                    
                    }
                    }
                    
                    if(isset($all_data[$key])){
                        //dd($data);
                        $all_data[$key] = $data;
                        array_push($new_record,$all_data[$key]);
                    }
                    
                    
                }else{
                    // if($data['update_in_type']=="ams"){
                        $data['in_time']= $data['in_time'];
                        $data['in_device']=$data['in_device'];
                        $data['update_in_type']='ams';
                    // }
                    // if($data['update_out_type']=="ams"){
                        $data['out_time']=$data['out_time'];
                        $data['out_device']=$data['out_device'];
                        $data['update_out_type']='ams';
                    // }
                    // if($data['in_time'] !="NA" && $data['out_time'] !="NA"){
                    //     array_push($delete_employee,$data['employee_id']);
                    // }
                    array_push($new_record,$data);
                    if(isset($all_data[$key])){
                        $all_data[$key] = $data;
                        array_push($new_record,$all_data[$key]);
                    }
                    
                }
                
            }
            foreach($delete_employee as $delete){
                //$this->deleteUser($delete);
            }
            if(!empty($new_record)){
                //dd($new_record);
                $VisitorHistory= AttendanceHistory::where(['last_synchronize_date'=>$start_date])->update(['ams_data'=>json_encode($new_record)]);
            }
            
        }
        return response()->json(['message'=>'Your Request SuccessfullY Submitted', 'class'=>'success']);
    }

     function sendFaceCheckAlotte($start_date,$end_date,$page_no){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://ams.facer.in/api/public/simplified',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
              "start_date": "'.$start_date.'",
              "end_date": "'.$end_date.'",
              "page": "'.$page_no.'"
            }',
              CURLOPT_HTTPHEADER => array(
                'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2ODE0NTM1NjB9.x4WjHei_PKDExR2-RQpYNLLXoJHhZMh33fBjlAijkOw',
              'Content-Type: text/plain'
              ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            //dd($response);
            return json_decode($response,true);
    } 


}
