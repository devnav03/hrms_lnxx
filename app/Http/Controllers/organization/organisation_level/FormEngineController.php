<?php

namespace App\Http\Controllers\organization\organisation_level;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Organisation;
use App\Models\FormEngine;
use App\Models\MapFormOrg;
use App\Models\EmployeeInfo;
use App\Models\FormEngineCategory;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use DB;
class FormEngineController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    public function AddForm(Request $request){
        $user_id = Auth::user()->id;
        $update=[];
        if(!empty($request->segment(2))){
            $update = FormEngine::where('id',$request->segment(2))->first();
        }
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $category = FormEngineCategory::where(['orgnization_id'=>$user_id])->get();
        $result = DB::select("SELECT a.id,a.order_id,b.name,a.form_name,a.data_type,a.group_name,a.is_fixed,a.created_at FROM `form_engines` as a INNER JOIN form_engine_categories as b on a.form_category_id=b.id WHERE b.orgnization_id=$user_id AND a.orgnization_id=$user_id ORDER BY a.order_id,b.id,a.group_name ASC");
        if(!empty($_POST)){
            if(!empty($request->update_id)){
                $forms = FormEngine::where('id',$request->update_id)->first();
                if($forms->is_fixed==1){
                    $forms->form_category_id=$request->form_category_id;
                    $forms->form_name=$request->form_name;
                    $forms->group_name=$request->group_name;
                    $forms->save();
                }else{
                    $forms->orgnization_id=$user_id;
                    $forms->form_category_id=$request->form_category_id;
                    $forms->form_column=str_replace(' ','_', strtolower($request->form_name));
                    $forms->form_name=$request->form_name;
                    $forms->data_type=$request->data_type;
                    $forms->group_name=$request->group_name;
                    $forms->save();
                }
                return redirect('add-form')->with('success','Updated successfuly');
            } else {
                $forms = new FormEngine();
                $forms->orgnization_id=$user_id;
                $forms->form_category_id=$request->form_category_id;
                $forms->form_column=str_replace(' ','_',strtolower($request->form_name));
                $forms->form_name=$request->form_name;
                $forms->data_type=$request->data_type;
                $forms->group_name=$request->group_name;
                $forms->save();
                return redirect('add-form')->with('success','Saved successfuly');
            }
        }
        return view('organization.organisation_level.add_form',compact('organisation','category','result','update'));
    }
    public function DeleteForm($id){
        FormEngine::where('id',$id)->delete();
        return redirect('add-form')->with('success','Deleted successfuly');
    }
    public function AddFormEngine(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
        $tables = DB::table('form_engines')->select('id','form_name','form_column')->get();
        $updt = MapFormOrg::select('id','form_name','is_required','required_name')->where('organisation_id',$user_id)->orderBy('id', 'ASC')->get();
        return view('organization.organisation_level.add_form_engine',compact('organisation','tables','updt'));
    }
    public function SaveFormEngine(Request $request){
        $user_id = Auth::user()->id;
        $updt = MapFormOrg::select('id','form_name','is_required','required_name')->where('organisation_id',$user_id)->orderBy('id', 'ASC')->get();
        if(!empty($_POST)){
            unset($_POST['_token']);
            $post = $_POST;
            MapFormOrg::where('organisation_id',$user_id)->whereNotIn('form_name',$post['yes'])->delete();
            $count = count($post['yes']);
            for($i=0;$i<$count;$i++){
                $update = MapFormOrg::select('id')->where('organisation_id',$user_id)->where('form_name',$post['yes'][$i])->first();
                if(!empty($update)){
                    $update->form_name = $post['yes'][$i];
                    $update->is_required = !empty($post[$post['yes'][$i]]) ? 1:0;
                    $update->editable = !empty($post[$post['yes'][$i].'_editable']) ? 1:0;
                    $update->required_name = !empty($post[$post['yes'][$i]]) ? $post[$post['yes'][$i]]:null;
                    $update->save();
                }else{
                    $map = new MapFormOrg();
                    $map->organisation_id = $user_id;
                    $map->form_name = $post['yes'][$i];
                    $map->is_required = !empty($post[$post['yes'][$i]]) ? 1:0;
                    $map->editable = !empty($post[$post['yes'][$i].'_editable']) ? 1:0;
                    $map->required_name = !empty($post[$post['yes'][$i]]) ? $post[$post['yes'][$i]]:null;
                    $map->save();
                }
            }
            return redirect('add-form-engine')->with('success','Saved successfuly');
        }
    }

    public function SendRegisterMail($data){
        $email = $data->email;
        try {
            $orgnisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
            $template_data = ['email' => $data->email, 'name' => $data->first_name.' '.$data->last_name,'password'=>$data->password,'user_name'=>$orgnisation->user_name];
            Mail::send(['html'=>'email.account_registration'], $template_data,
                function ($message) use ($email) {
                    $message->to($email)->from('lnxxapp@gmail.com')->subject('Account registration');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    
    public function SaveForm(Request $request){
        $user_id = Auth::user()->id;
          $image_data = '';
        if(!empty($_POST['emp_code'])){
            $users = User::select('id','name')->where('id',$_POST['emp_code'])->first();
        }else{
            $prifix = Setting::select('emp_prifix')->where('orgnization_id',$user_id)->first();
            $users = new User();
            $users->name = $request->first_name.' '.$request->second_name.' '.$request->last_name;    
            $lnxx_login=$request->lnxx_login;
            $users->email = $request->email;
            $users->salary = $request->salary;
            if(isset($request->mobile)){
            $users->mobile = $request->mobile;
            } else {
            $users->mobile = $request->uae_mobile_no;
            }
            $users->type = 2;
            /*if(!empty($prifix->emp_prifix)){
                $users->password = Hash::make(strtoupper($prifix->emp_prifix).'@123#');
            }else{
                $users->password = $request->first_name.'@123#';
            }*/
            if(!empty($prifix->emp_prifix)){
                $users->password = Hash::make(strtoupper($prifix->emp_prifix).'@123#');
                $lnxx_password=$prifix->emp_prifix.'@123#';
            }else{
                $users->password = Hash::make(strtoupper($request->first_name).'@123#');
                $lnxx_password=$request->first_name.'@123#';
            }
            $users->status = 'Active';
            $users->organisation_id = $user_id;
            $users->lnxx_login=$request->lnxx_login;
            $users->save();

            
           //dd($image_data);
           

           // dd($add_face_chk);
           
            if ($lnxx_login=='1') {
                $curl = curl_init();  
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://vztor.in/api/v1/insert-user',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('salutation' => '','name' => $request->first_name,'middle_name' => $request->middle_name,'last_name' => $request->last_name,'email' => $request->email,'mobile'=> $request->uae_mobile_no,'user_type' => '2','date_of_birth' => $request->dob, 'profile_image'=>'','status'=>'1','password'=>$lnxx_password),));

                $response = curl_exec($curl);
                //echo "<pre>"; print_r($response); echo "</pre>"; die; 
                curl_close($curl);
            }

        }
        unset($_POST['_token']);
        unset($_POST['emp_code']);
        $form_engine_cat = FormEngineCategory::select('id','name','is_multiple')->where('name',$_POST['forms_name'])->where('orgnization_id',$user_id)->first();
        unset($_POST['forms_name']);
        $emp_info = new EmployeeInfo();
        $emp_info->organisation_id = $user_id;
        $emp_info->user_id = $users->id;
        $emp_info->from_cat_id = $form_engine_cat->id;
        if(!empty($_POST['employee_code'])){
            $emp_info->employee_code = $_POST['employee_code'];
            if(!empty($prifix->emp_prifix)){
                $users->password = strtoupper($prifix->emp_prifix).'@123#';
            }else{
                 $users->password = $request->first_name.'@123#';
            }
            $this->SendRegisterMail($users);
        }
        // else{
        //     if(!empty($prifix)){
        //         $emp_info->employee_code = strtoupper($prifix->emp_prifix).str_pad($users->id, 4, "0", STR_PAD_LEFT);
        //         $users->password = $request->first_name.'@123#';
        //         //$this->SendRegisterMail($users);
        //     }
        // }
        
        $form_engine1 = FormEngine::select('form_column')->where('form_category_id',$form_engine_cat->id)->where('data_type','file')->where('orgnization_id',$user_id)->get();
        if(!empty($form_engine1)){
            foreach($form_engine1 as $fo_en){
                if(!empty($_FILES[$fo_en->form_column]['name'])){
                    $file_name = $_FILES[$fo_en->form_column]['name'];
                    if($form_engine_cat->is_multiple==1){
                       if($fo_en->form_column == 'profile_image'){
                         //dd('here');
                        }
                        $form_count = count($file_name);
                        for($i=0; $i < $form_count;$i++){
                            $filename = $file_name[$i];
                            $request[$fo_en->form_column][$i]->move(public_path('employee/'.$fo_en->form_column.''),$filename);
                            $_POST[$fo_en->form_column][$i]='employee/'.$fo_en->form_column.'/'.$filename;

                        }
                    }else{
                        if(!empty($file_name)){
                            $filename = $file_name;
                           
                            $request[$fo_en->form_column]->move(public_path('employee/'.$fo_en->form_column.''),$filename);
                            $_POST[$fo_en->form_column]='employee/'.$fo_en->form_column.'/'.$filename;

                            if($fo_en->form_column == 'profile_image'){
                            $path = public_path('employee/'.$fo_en->form_column.'/'.$filename);                          
                            $type = pathinfo($path, PATHINFO_EXTENSION); // get the image extension
                            $data = file_get_contents($path); // get the contents of the image file
                            $image_data = base64_encode($data); 
                           }

                        }
                    }
                }
            }
        }
        //dd($image_data);
        $refer_code = $request->employee_code;
            $all_data['name'] = $request->first_name.' '.$request->last_name;
            $all_data['email'] = $request->email;
            $all_data['mobile'] = $request->mobile;
          
       // $add_face_chk = $this->sendFaceCheck($all_data,$refer_code,$image_data);
       
       // $res=$this->sendFaceCheckAlotte($refer_code);
        //dd($res);
        // dd($add_face_chk);   

        if(isset($_POST['shift_id'])) {
        $emp_info->shift_id = $_POST['shift_id']; 
        }
        $emp_info->update_data = json_encode($_POST);
        $form_engine2 = FormEngine::select('form_column','master_table')->where('form_category_id',$form_engine_cat->id)->where('form_column_id',1)->where('orgnization_id',$user_id)->get();
        if(!empty($form_engine2)){
            foreach($form_engine2 as $fo_eng){
                $select = DB::table($fo_eng->master_table)->where('orgnization_id',$user_id)->where('id',$_POST[$fo_eng->form_column])->first();
                if(!empty($select->office_name)){
                    $emp_info->office_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->office_name;
                }elseif(!empty($select->department_name)){
                    $emp_info->department_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->department_name;
                }elseif(!empty($select->position_name)){
                    $emp_info->position_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->position_name;
                }
            }
        }
        $emp_info->datas = json_encode($_POST);
        if(isset($_POST['shift_id'])){
        $emp_info->shift_id = $_POST['shift_id']; 
        }
        $emp_info->save();
        $next = $form_engine_cat->id;
        $formenginecat = FormEngineCategory::select('name')->where('id','>',$next)->where('orgnization_id',$user_id)->first();
        if(!empty($formenginecat)){
            $formenginecat = str_replace(' ', '-', strtolower($formenginecat->name));
            return redirect('add-employeess/'.$users->id.'?page='.$formenginecat)->with('success','Saved successfuly');
        }else{
            return redirect('add-employeess')->with('success','Saved successfuly');
        }
    }
    function sendFaceCheck($all_data,$visitor_id,$image_data){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://ams.facer.in/api/public/employee/add',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "office_name": "Shailers Solution Private Limited",
          "department_name": "IT",
          "shift_name": "General Shift",
          "employee_name": "'.$all_data['name'].'",
          "employee_id": "'.$visitor_id.'",
          "employee_gender": "Male",
          "employee_image": "'.$image_data.'",
          "employee_email": "'.$all_data['email'].'",
          "employee_contact_number": "'.$all_data['mobile'].'",
          "contract_type": "PERMANENT",
          "overtime": "30",
          "status": "ACTIVE",
          "date": "2023-04-13"
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2ODEzNzM0ODV9._M_2ogJBiERUMXlpbblrbXZVIoO60FSnoaMTGeWleyE',
            'Content-Type: text/plain'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response,true);
    }
    
    
    function sendFaceCheckAlotte($employee_id){
    $devices=$this->getDeviceAllocateUser($employee_id);
    $devices_name=json_encode($devices);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://ams.facer.in/api/public/employee/allot',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "employee_id": "'.$employee_id.'",
        "allotments": '.$devices_name.'
      }',
       CURLOPT_HTTPHEADER => array(
         'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2ODEzNzM0ODV9._M_2ogJBiERUMXlpbblrbXZVIoO60FSnoaMTGeWleyE',
         'Content-Type: text/plain'
       ),
     ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
    }
    public function getDeviceAllocateUser($user_id){
        $device_details=[];
        $device_details[0]['device_name'] = '7 Inch 1810584';
        $device_details[0]['office_name'] = 'Shailers Solution Private Limited';
        return $device_details;
    }
    public function SaveUpdatedProfile(Request $request){
        $user_id = Auth::user()->id;
        $users = User::select('id','name')->where('id',$_POST['emp_code'])->first();
        unset($_POST['_token']);
        unset($_POST['emp_code']);
        $form_engine_cat = FormEngineCategory::select('id','name','is_multiple')->where('name',$_POST['forms_name'])->where('orgnization_id',$user_id)->first();
        unset($_POST['forms_name']);
        if(!empty($_POST['employee_code'])){
            $users->name = $request->first_name.' '.$request->second_name.' '.$request->last_name;
            $users->email = $request->email;
            $users->salary = $request->salary;
            $users->save();
        }
        $emp_info = EmployeeInfo::where('user_id',$users->id)->where('from_cat_id',$form_engine_cat->id)->where('organisation_id',$user_id)->first();
        if(empty($emp_info)){
            $emp_info = new EmployeeInfo();
            $emp_info->organisation_id = $user_id;
            $emp_info->user_id = $users->id;
            if(isset($users->shift_id))
            $emp_info->shift_id = $users->shift_id;
            $emp_info->from_cat_id = $form_engine_cat->id;
        }
        $form_engine1 = FormEngine::select('form_column')->where('form_category_id',$form_engine_cat->id)->where('data_type','file')->where('orgnization_id',$user_id)->get();
        if(!empty($form_engine1)){
            foreach($form_engine1 as $fo_en){
                if(!empty($_FILES[$fo_en->form_column]['name'])){
                    $file_name = $_FILES[$fo_en->form_column]['name'];
                    if($form_engine_cat->is_multiple==1){
                        $form_count = count($file_name);
                        for($i=0; $i < $form_count;$i++){
                            $filename = $file_name[$i];
                            if(!empty($filename)){
                                $request[$fo_en->form_column][$i]->move(public_path('employee/'.$fo_en->form_column.''),$filename);
                                $_POST[$fo_en->form_column][$i]='employee/'.$fo_en->form_column.'/'.$filename;
                            }
                        }
                    }else{
                        if(!empty($file_name)){
                            $filename = $file_name;
                            $request[$fo_en->form_column]->move(public_path('employee/'.$fo_en->form_column.''),$filename);
                            $_POST[$fo_en->form_column]='employee/'.$fo_en->form_column.'/'.$filename;
                        }
                    }
                }
            }
        }
        $emp_info->update_data = json_encode($_POST);
        if(isset($_POST['shift_id'])) {
        $emp_info->shift_id = $_POST['shift_id']; 
        }
        $form_engine2 = FormEngine::select('form_column','master_table')->where('form_category_id',$form_engine_cat->id)->where('form_column_id',1)->where('orgnization_id',$user_id)->get();
        if(!empty($form_engine2)){
            foreach($form_engine2 as $fo_eng){
                $select = DB::table($fo_eng->master_table)->where('orgnization_id',$user_id)->where('id',$_POST[$fo_eng->form_column])->first();
                if(!empty($select->office_name)){
                    $emp_info->office_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->office_name;
                }elseif(!empty($select->department_name)){
                    $emp_info->department_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->department_name;
                }elseif(!empty($select->position_name)){
                    $emp_info->position_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->position_name;
                }
            }
        }
        $emp_info->datas = json_encode($_POST);
        if(isset($_POST['shift_id'])){
        $emp_info->shift_id = $_POST['shift_id']; 
        }
        $emp_info->save();
        return redirect('employee-details')->with('success','Updated successfuly');
    }
    public function SaveEmpUpdatedProfile(Request $request){
        $user_id = Auth::user();
        $users = User::select('id','name')->where('id',$_POST['emp_code'])->first();
        unset($_POST['_token']);
        unset($_POST['emp_code']);
        $form_engine_cat = FormEngineCategory::select('id','name','is_multiple')->where('name',$_POST['forms_name'])->where('orgnization_id',$user_id->organisation_id)->first();
        unset($_POST['forms_name']);
        if(!empty($_POST['employee_code'])){
            $users->name = $request->first_name.' '.$request->second_name.' '.$request->last_name;
            $users->email = $request->email;

            $users->save();
        }
        $emp_info = EmployeeInfo::where('user_id',$users->id)->where('from_cat_id',$form_engine_cat->id)->where('organisation_id',$user_id->organisation_id)->first();
        if(empty($emp_info)){
            $emp_info = new EmployeeInfo();
            $emp_info->organisation_id = $user_id;
            $emp_info->user_id = $users->id;
            $emp_info->shift_name = $users->shift_name;
            $emp_info->from_cat_id = $form_engine_cat->id;
        }
        $form_engine1 = FormEngine::select('form_column')->where('form_category_id',$form_engine_cat->id)->where('data_type','file')->where('orgnization_id',$user_id->organisation_id)->get();
        if(!empty($form_engine1)){
            foreach($form_engine1 as $fo_en){
                if(!empty($_FILES[$fo_en->form_column]['name'])){
                    $file_name = $_FILES[$fo_en->form_column]['name'];
                    if($form_engine_cat->is_multiple==1){
                        $form_count = count($file_name);
                        for($i=0; $i < $form_count;$i++){
                            $filename = $file_name[$i];
                            if(!empty($filename)){
                                $request[$fo_en->form_column][$i]->move(public_path('employee/'.$fo_en->form_column.''),$filename);
                                $_POST[$fo_en->form_column][$i]='employee/'.$fo_en->form_column.'/'.$filename;
                            }
                        }
                    }else{
                        if(!empty($file_name)){
                            $filename = $file_name;
                            $request[$fo_en->form_column]->move(public_path('employee/'.$fo_en->form_column.''),$filename);
                            $_POST[$fo_en->form_column]='employee/'.$fo_en->form_column.'/'.$filename;
                        }
                    }
                }
            }
        }

        if(isset($_POST['shift_id'])){
        $emp_info->shift_id = $_POST['shift_id']; 
        }
        $emp_info->update_data = json_encode($_POST);


        $form_engine2 = FormEngine::select('form_column','master_table')->where('form_category_id',$form_engine_cat->id)->where('form_column_id',1)->where('orgnization_id',$user_id->organisation_id)->get();
        if(!empty($form_engine2)){
            foreach($form_engine2 as $fo_eng){
                $select = DB::table($fo_eng->master_table)->where('orgnization_id',$user_id->organisation_id)->where('id',$_POST[$fo_eng->form_column])->first();
                if(!empty($select->office_name)){
                    $emp_info->office_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->office_name;
                }elseif(!empty($select->department_name)){
                    $emp_info->department_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->department_name;
                }elseif(!empty($select->position_name)){
                    $emp_info->position_id = $_POST[$fo_eng->form_column];
                    $_POST[$fo_eng->form_column]=$select->position_name;
                }
            }
        }
        $emp_info->datas = json_encode($_POST);
        if(isset($_POST['shift_id'])){
        $emp_info->shift_id = $_POST['shift_id']; 
        }
        $emp_info->save();
        return redirect('self/employee-details')->with('success','Updated successfuly');
    }
}
