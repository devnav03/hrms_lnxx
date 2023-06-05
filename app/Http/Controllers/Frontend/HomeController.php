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
use App\Models\SendVisaApproval;
use App\Models\VanderStaff;
use App\Models\ShiftMaster;
use App\Models\ShiftOfDay;
use App\Models\HolidayCalendar;
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
     

        /*======================START HERE VIKAS CODE===========================*/


    ####################Accept eVisa Approval Mail ###############

        public function evisa_approved($token){
        $id = \decrypt($token);
        $evisa = SendVisaApproval::where('candidate_id', $id)->first();
        if(empty($evisa)){
            echo "Something went wrong."; exit;
        } else {
            if($evisa->visa_approved_reject_status == '0'){
            $user_id = $evisa->organisation_id;
            $date = date('Y-m-d H:i:s');
            $results = DB::table('send_visa_approvals')->where(['organisation_id'=>$user_id,'candidate_id'=>$id])->update(['visa_approved_date'=>$date,'visa_approved_reject_status' => '1']);
           $data = SendHrRequest::select('candidate_name','candidate_email','hr_email')->where('id', $id)->first();

           $result1 = SendHrRequest::where('id', $id)->first();
           $result1->hiring_status = 5;
           $result1->save();

           $hr_email=$data->hr_email;

            $reponse=(object)[
                'name'    => $data->candidate_name,
                'email'   => $data->candidate_email,
                'status'  => 'Approved',
            ];

            $this->SendEVisaApprovalStatusMail($reponse, $hr_email);
                echo "e-Visa approved successfully."; exit;
            }
            if($evisa->visa_approved_reject_status == 1){
                echo "This e-Visa is already approved."; exit;
            }
            if($evisa->visa_approved_reject_status == 2){
                echo "This e-Visa is already rejected."; exit;
            }
        }
    }
    
    public function holiday_calendar_crone(){

        $month = date('m');
        $year = date('Y');
        $shifts = ShiftMaster::select('id')->get();

        if(count($shifts) != 0){
            foreach ($shifts as $shift) {
                $holiday_calendars = HolidayCalendar::where('year', $year)->where('month', $month)->select('id', 'day')->get();
                if(count($holiday_calendars)){
                    foreach ($holiday_calendars as $holiday_calendar) {
                        $date = $year.'-'.$month.'-'.$holiday_calendar->day;
                        $check = ShiftOfDay::where('day', $date)->where('shift_id', $shift->id)->count();
                        if($check == 0){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $shift->id;
                            $leave->month = date('Y-m');
                            $leave->day = $date;
                            $leave->save();
                        } 
                    }
                }
            }
        }
    }

    public function shift_day_crone(){

        $holidays_info = ShiftMaster::select('holidays', 'id')->get();
        $old_date = date('Y-m');
        \DB::table('shift_of_days')->where('month', $old_date)->delete();
         
        if(count($holidays_info) != 0){
            $week_of_month=['First','Second','Third','Fourth','Fifth'];

            foreach ($holidays_info as $holiday) {

                $days = json_decode($holiday->holidays);
                
                if($days) {

                    $monday = []; 
                    if(isset($days[0]->MONDAY)) {
                        $monday = $days[0]->MONDAY;
                    } else if(isset($days[1]->MONDAY)) {
                        $monday = $days[1]->MONDAY; 
                    } else if(isset($days[2]->MONDAY)) {
                        $monday = $days[2]->MONDAY;
                    } else if(isset($days[3]->MONDAY)) {
                        $monday = $days[3]->MONDAY;
                    } else if(isset($days[4]->MONDAY)) {
                        $monday = $days[4]->MONDAY;
                    } else if(isset($days[5]->MONDAY)) {
                        $monday = $days[5]->MONDAY;
                    } else if(isset($days[6]->MONDAY)) {
                        $monday = $days[6]->MONDAY;
                    }
                
                if(count($monday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 1) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $monday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $monday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $monday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $monday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $monday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

                $tuesday = []; 
                    if(isset($days[0]->TUESDAY)) {
                        $tuesday = $days[0]->TUESDAY;
                    } else if(isset($days[1]->TUESDAY)) {
                        $tuesday = $days[1]->TUESDAY; 
                    } else if(isset($days[2]->TUESDAY)) {
                        $tuesday = $days[2]->TUESDAY;
                    } else if(isset($days[3]->TUESDAY)) {
                        $tuesday = $days[3]->TUESDAY;
                    } else if(isset($days[4]->TUESDAY)) {
                        $tuesday = $days[4]->TUESDAY;
                    } else if(isset($days[5]->TUESDAY)) {
                        $tuesday = $days[5]->TUESDAY;
                    } else if(isset($days[6]->TUESDAY)) {
                        $tuesday = $days[6]->TUESDAY;
                    }
                
                if(count($tuesday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 2) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $tuesday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $tuesday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $tuesday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $tuesday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $tuesday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

                $wednesday = []; 
                    if(isset($days[0]->WEDNESDAY)) {
                        $wednesday = $days[0]->WEDNESDAY;
                    } else if(isset($days[1]->WEDNESDAY)) {
                        $wednesday = $days[1]->WEDNESDAY; 
                    } else if(isset($days[2]->WEDNESDAY)) {
                        $wednesday = $days[2]->WEDNESDAY;
                    } else if(isset($days[3]->WEDNESDAY)) {
                        $wednesday = $days[3]->WEDNESDAY;
                    } else if(isset($days[4]->WEDNESDAY)) {
                        $wednesday = $days[4]->WEDNESDAY;
                    } else if(isset($days[5]->WEDNESDAY)) {
                        $wednesday = $days[5]->WEDNESDAY;
                    } else if(isset($days[6]->WEDNESDAY)) {
                        $wednesday = $days[6]->WEDNESDAY;
                    }
                
                if(count($wednesday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 3) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $wednesday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $wednesday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $wednesday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $wednesday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $wednesday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

                $thursday = []; 
                    if(isset($days[0]->THURSDAY)) {
                        $thursday = $days[0]->THURSDAY;
                    } else if(isset($days[1]->THURSDAY)) {
                        $thursday = $days[1]->THURSDAY; 
                    } else if(isset($days[2]->THURSDAY)) {
                        $thursday = $days[2]->THURSDAY;
                    } else if(isset($days[3]->THURSDAY)) {
                        $thursday = $days[3]->THURSDAY;
                    } else if(isset($days[4]->THURSDAY)) {
                        $thursday = $days[4]->THURSDAY;
                    } else if(isset($days[5]->THURSDAY)) {
                        $thursday = $days[5]->THURSDAY;
                    } else if(isset($days[6]->THURSDAY)) {
                        $thursday = $days[6]->THURSDAY;
                    }
                
                if(count($thursday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 4) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $thursday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $thursday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $thursday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $thursday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $thursday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

                $friday = []; 
                    if(isset($days[0]->FRIDAY)) {
                        $friday = $days[0]->FRIDAY;
                    } else if(isset($days[1]->FRIDAY)) {
                        $friday = $days[1]->FRIDAY; 
                    } else if(isset($days[2]->FRIDAY)) {
                        $friday = $days[2]->FRIDAY;
                    } else if(isset($days[3]->FRIDAY)) {
                        $friday = $days[3]->FRIDAY;
                    } else if(isset($days[4]->FRIDAY)) {
                        $friday = $days[4]->FRIDAY;
                    } else if(isset($days[5]->FRIDAY)) {
                        $friday = $days[5]->FRIDAY;
                    } else if(isset($days[6]->FRIDAY)) {
                        $friday = $days[6]->FRIDAY;
                    }
                
                if(count($friday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 5) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $friday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $friday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $friday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $friday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $friday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

                $saturday = []; 
                    if(isset($days[0]->SATURDAY)) {
                        $saturday = $days[0]->SATURDAY;
                    } else if(isset($days[1]->SATURDAY)) {
                        $saturday = $days[1]->SATURDAY; 
                    } else if(isset($days[2]->SATURDAY)) {
                        $saturday = $days[2]->SATURDAY;
                    } else if(isset($days[3]->SATURDAY)) {
                        $saturday = $days[3]->SATURDAY;
                    } else if(isset($days[4]->SATURDAY)) {
                        $saturday = $days[4]->SATURDAY;
                    } else if(isset($days[5]->SATURDAY)) {
                        $saturday = $days[5]->SATURDAY;
                    } else if(isset($days[6]->SATURDAY)) {
                        $saturday = $days[6]->SATURDAY;
                    }
                
                if(count($saturday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 6) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $saturday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $saturday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $saturday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $saturday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $saturday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

                $sunday = []; 
                    if(isset($days[0]->SUNDAY)) {
                        $sunday = $days[0]->SUNDAY;
                    } else if(isset($days[1]->SUNDAY)) {
                        $sunday = $days[1]->SUNDAY; 
                    } else if(isset($days[2]->SUNDAY)) {
                        $sunday = $days[2]->SUNDAY;
                    } else if(isset($days[3]->SUNDAY)) {
                        $sunday = $days[3]->SUNDAY;
                    } else if(isset($days[4]->SUNDAY)) {
                        $sunday = $days[4]->SUNDAY;
                    } else if(isset($days[5]->SUNDAY)) {
                        $sunday = $days[5]->SUNDAY;
                    } else if(isset($days[6]->SUNDAY)) {
                        $sunday = $days[6]->SUNDAY;
                    }
                
                if(count($sunday) != 0){
                    $year = date('Y');
                    $month = date('m');
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $mondays = array();
                    for ($day = 1; $day <= $numDays; $day++) {
                        $date = strtotime("$year-$month-$day");
                        if (date('N', $date) == 7) { 
                            $mondays[] = date('Y-m-d', $date);
                        }
                    }

                    if(in_array('First', $sunday)){
                        if(isset($mondays[0])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[0];
                            $leave->save();
                        }
                    }
                    if(in_array('Second', $sunday)){
                        if(isset($mondays[1])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[1];
                            $leave->save();
                        }
                    }
                    if(in_array('Third', $sunday)){
                        if(isset($mondays[2])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[2];
                            $leave->save();
                        }
                    }
                    if(in_array('Fourth', $sunday)){
                        if(isset($mondays[3])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[3];
                            $leave->save();
                        }
                    }
                    if(in_array('Fifth', $sunday)){
                        if(isset($mondays[4])){
                            $leave = new ShiftOfDay();
                            $leave->shift_id = $holiday->id;
                            $leave->month = date('Y-m');
                            $leave->day = $mondays[4];
                            $leave->save();
                        }
                    }
                }

            }

            }

        }
    }
    
    public function getPro(Request $request){
        if($request->company_id == 0){

            $user_id = Auth::user()->id;
            $user_org = User::where('id', $user_id)->select('organisation_id')->first();
            $pos_name = 'pro';    
            $designation = PositionMaster::where('orgnization_id', $user_org->organisation_id)
            ->where('position_name', 'like', '%' . $pos_name . '%')->pluck('id')->toArray();

            $pros = \DB::table('users')
            ->join('employee_infos', 'employee_infos.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'employee_infos.employee_code')->whereIn('employee_infos.position_id', $designation)->where('employee_infos.employee_code', '!=', NULL)->get(); 

            $subcategoryList='';
            foreach($pros as $key => $subcategory)
            $subcategoryList .= '<option value="' . $subcategory->id . '">'. $subcategory->name .' ('. $subcategory->employee_code .')</option>';
            return $subcategoryList; 

        } else {

            $pros = VanderStaff::where('status', 'Active')->where('vander_id', $request->company_id)->select('name', 'email', 'id')->get();

            $subcategoryList='';
            foreach($pros as $key => $subcategory)
            $subcategoryList .= '<option value="' . $subcategory->id . '">'. $subcategory->name .' ('. $subcategory->email .')</option>';
            return $subcategoryList; 

        }

    }

    public function candidate_profile($id = null){

        try {
            
            $id = \decrypt($id);
            $OfferLetters = SendOfferLettersToCandidate::where('candidate_id', $id)->first();
            if(empty($OfferLetters)){
                echo "Something went wrong"; exit;
            } else { 

                $org = Organisation::where('user_id', $OfferLetters->organisation_id)->select('logo')->first();
                $SendHrRequest = SendHrRequest::where('id', $id)->first();
                $candidate_id = $id;

                $position = PositionMaster::where('id', $SendHrRequest->candidate_position_id)->select('position_name')->first();

                   
                return view('user.employee.candidate_profile', compact('id', 'org', 'OfferLetters', 'SendHrRequest', 'candidate_id', 'position'));
             
            }

        } catch (Exception $ex) {
            return false;
        }  

    }

    #################### Reject eVisa Approval Mail ###############
        public function evisa_rejected($token){
        $id = \decrypt($token);
        $evisa = SendVisaApproval::where('candidate_id', $id)->first();
        if(empty($evisa)){
            echo "Something went wrong."; exit;
        } else {
            if($evisa->visa_approved_reject_status == '0'){
            $user_id = $evisa->organisation_id;
           
           $date = date('Y-m-d H:i:s'); 
           $results = DB::table('send_visa_approvals')->where(['organisation_id'=>$user_id,'candidate_id'=>$id])->update(['visa_rejected_date'=>$date,'visa_approved_reject_status' => '2']);
           
           $data = SendHrRequest::select('candidate_name','candidate_email','hr_email')->where('id',$id)->first();

           $result1 = SendHrRequest::where('id', $id)->first();
           $result1->hiring_status = 6;
           $result1->save();


           $hr_email=$data->hr_email;
            $reponse=(object)[
                'name'              =>$data->candidate_name,
                'email'             =>$data->candidate_email,
                'status'            =>'Rejected',
            ];

            $this->SendEVisaApprovalStatusMail($reponse, $hr_email);
                echo "e-Visa rejected successfully."; exit;
            }
            if($evisa->visa_approved_reject_status == 1){
                echo "This e-Visa is already approved."; exit;
            }
            if($evisa->visa_approved_reject_status == 2){
                echo "This e-Visa is already rejected."; exit;
            }
        }
    }

    /*========COMMON MAIL FUNCTION ACCEPT REJECT=======*/
    public function SendEVisaApprovalStatusMail($data,$hr_email){
       // dd($data);
        $user_id = Auth::user()->id;
        $email = array($hr_email);
        try {
            $template_data = [
                'name'    => $data->name,
                'email'   => $data->email,
                'status' => $data->status,
             ];
             Mail::send(['html'=>'email.evisaapprovaltohr'], $template_data,
                function ($message) use ($email,$template_data) {
                    $message->to($email)->from("lnxx@gmail.com")->subject('eVisa status '.$template_data['status'] . 'of ' .$template_data['name']);
            }); 
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    /*======================END HERE VIKAS CODE===========================*/



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
