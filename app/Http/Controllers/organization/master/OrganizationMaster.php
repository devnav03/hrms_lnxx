<?php

namespace App\Http\Controllers\organization\master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\SourceMaster;
use App\Models\Organisation;
use App\Models\PositionMaster;
use App\Models\NoticeMaster;
use App\Models\EducationMaster;
use App\Models\ProjectMaster;
use App\Models\ProjectActivity;
use App\Models\User;
use App\Models\FormEngineCategory;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\HeaderFooterTemplateMaster;
use App\Models\ShiftMaster;
use App\Models\EmpAttendance;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\BankMaster;
use App\Models\State;
use App\Models\City;
use App\Models\EmpType;
use App\Models\Country;
use App\Models\WeekDay;
use App\Models\AssignTask;
use App\Models\ShiftDuration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\TemplateMaster;

use DB;

class OrganizationMaster extends Controller
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
       return Organisation::where(['user_id'=>$user_id])->first();
    }
    public function AddSource(Request $request){
        $user_id = Auth::user()->id;
        $update=[];
        if(!empty($request->segment(2))){
            $update = SourceMaster::where('id',$request->segment(2))->first();
        }
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($request->source_name)){
            $sourceMaster = SourceMaster::where('source_name', '=', $request->source_name)->where('orgnization_id',$user_id)->first();
            if(empty($sourceMaster)){
                if($request->update_id>0){
                    $sourceMaster = SourceMaster::where('id',$request->update_id)->first();
                    $sourceMaster->orgnization_id = Auth::user()->id;
                    $sourceMaster->source_name = $request->source_name;
                    $sourceMaster->save();
                    return redirect('add-source')->with('success','Updated successfuly');
                }else{
                    $sourceMaster = new SourceMaster();
                    $sourceMaster->orgnization_id = Auth::user()->id;
                    $sourceMaster->source_name = $request->source_name;
                    $sourceMaster->save();
                    return redirect('add-source')->with('success','Saved successfuly');
                }
            }else{
                return redirect('add-source')->with('error','Source Name Already Exist');
            }
        }
        return view('organization.master.add_source',compact('organisation','update'));
    }

    public function logoutUser(){

        $user_id = Auth::user()->id;
        $link = Organisation::where('user_id', $user_id)->select('user_name')->first();
        \Auth::logout();
        \Session::flush();
        return redirect($link->user_name);
    } 

    public function AddPosition(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $update=[];
        $department=[];
        $position_master=[];
        if(!empty($request->segment(2))){
            $update = PositionMaster::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
            $position_master = PositionMaster::select('id','position_name','sub_position')->where('orgnization_id',$user_id)->where('office_id',$update->office_id)->where('department_id',$update->department_id)->get();
        }
        $results = DB::select("SELECT a.id,a.position_name,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,a.type_of_position,a.sub_position FROM `position_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");

        $posti = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->first();
        $posti1 = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->first();
        if(!empty($posti)){
            $position = $posti;
        }elseif(!empty($posti1)){
            $position = $posti1;
        }
        if(!empty($request->position_name)){
            $position = PositionMaster::where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('position_name',$request->position_name)->where('orgnization_id',$user_id)->first();
            if(empty($position)){
                if($request->update_id>0){
                    $letterMaster = PositionMaster::where('id',$request->update_id)->first();
                    $letterMaster->orgnization_id = Auth::user()->id;
                    $letterMaster->office_id = $request->office_id;
                    $letterMaster->department_id = $request->department_id;
                    $letterMaster->position_name = $request->position_name;
                    if($request->type_of_position=='position_id'){
                        $slect = PositionMaster::select('position_name')->where('id',$request->parent_id)->first();
                        $letterMaster->type_of_position=1;
                        $letterMaster->parent_id = $request->parent_id;
                        $letterMaster->sub_position = $slect->position_name;
                    }else{
                        $letterMaster->parent_id=0;
                    }
                    $letterMaster->save();
                    return redirect('add-position')->with('success','Updated successfuly');
                }else{
                    $letterMaster = new PositionMaster();
                    $letterMaster->orgnization_id = Auth::user()->id;
                    $letterMaster->office_id = $request->office_id;
                    $letterMaster->department_id = $request->department_id;
                    $letterMaster->position_name = $request->position_name;
                    if($request->type_of_position=='position_id'){
                        $slect = PositionMaster::select('position_name')->where('id',$request->parent_id)->first();
                        $letterMaster->type_of_position=1;
                        $letterMaster->parent_id = $request->parent_id;
                        $letterMaster->sub_position = $slect->position_name;
                    }else{
                        $letterMaster->parent_id=0;
                    }
                    $letterMaster->save();
                    return redirect('add-position')->with('success','Saved successfuly');
                }
            }else{
                return redirect('add-position')->with('error','Position Name Already Exist');
            }
        }
        return view('organization.master.add_position',compact('organisation','update','office','department','position_master','results'));
    }


    public function UpdatePosition(Request $request){
        $user_id = Auth::user()->id;
        if($request->type_of_position=='position_id'){
            $position = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->first();
        }else{
            $pos = PositionMaster::select('id','type_of_position')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->first();
            if($pos->type_of_position==1){
                $position = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->first();
                $letterMaster = PositionMaster::where('id',$request->update_id)->first();
                $letterMaster->orgnization_id = Auth::user()->id;
                $letterMaster->office_id = $request->office_id;
                $letterMaster->department_id = $request->department_id;
                $letterMaster->position_name = $request->position_name;
                $letterMaster->type_of_position=0;
                $letterMaster->parent_id = 0;
                $letterMaster->sub_position = null;
                $letterMaster->save();
                return redirect('add-position')->with('success','Updated successfuly');
            }else{
                $position = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->first();
            }
        }
        if(empty($position)){
            $letterMaster = PositionMaster::where('id',$request->update_id)->first();
            $letterMaster->orgnization_id = Auth::user()->id;
            $letterMaster->office_id = $request->office_id;
            $letterMaster->department_id = $request->department_id;
            $letterMaster->position_name = $request->position_name;
            if($request->type_of_position=='position_id'){
                $slect = PositionMaster::select('position_name')->where('id',$request->parent_id)->first();
                $letterMaster->type_of_position=1;
                $letterMaster->parent_id = $request->parent_id;
                $letterMaster->sub_position = $slect->position_name;
            }else{
                $letterMaster->parent_id=0;
            }
            $letterMaster->save();
            return redirect('add-position')->with('success','Updated successfuly');
        }else{
            return redirect('add-position')->with('error','Position Name Already Exist');
        }
    }
    
    public function SavePosition(Request $request){
        $user_id = Auth::user()->id;
        if($request->type_of_position=='position_id'){
            $position = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->first();
        }else{
            $position = PositionMaster::select('id')->where('position_name',$request->position_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->first();
        }
        if(empty($position)){
            $letterMaster = new PositionMaster();
            $letterMaster->orgnization_id = Auth::user()->id;
            $letterMaster->office_id = $request->office_id;
            $letterMaster->department_id = $request->department_id;
            $letterMaster->position_name = $request->position_name;
            if($request->type_of_position=='position_id'){
                $slect = PositionMaster::select('position_name')->where('id',$request->parent_id)->first();
                $letterMaster->type_of_position=1;
                $letterMaster->parent_id = $request->parent_id;
                $letterMaster->sub_position = $slect->position_name;
            }else{
                $letterMaster->parent_id=0;
            }
            $letterMaster->save();
            return redirect('add-position')->with('success','Saved successfuly');
        }else{
            return redirect('add-position')->with('error','Position Name Already Exist');
        }
    }

    
    public function AddNoticePeriod(Request $request){
        $user_id = Auth::user()->id;
        $days = "Days";
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $update=[];
        $department=[];
        $position=[];
        if(!empty($request->segment(2))){
            $update = NoticeMaster::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
            $position = PositionMaster::where('department_id',$update->department_id)->get();
        }
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($request->notice_days)){
            $notice_days = NoticeMaster::where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('position_id',$request->position_id)->where('notice_days',$request->notice_days.' '.$days)->where('orgnization_id',$user_id)->first();
            if(empty($notice_days)){
                if($request->update_id>0){
                    $noticeMaster = NoticeMaster::where('id',$request->update_id)->first();
                    $noticeMaster->orgnization_id = Auth::user()->id;
                    $noticeMaster->office_id = $request->office_id;
                    $noticeMaster->department_id = $request->department_id;
                    $noticeMaster->position_id = $request->position_id;
                    $noticeMaster->notice_days = $request->notice_days;
                    $noticeMaster->save();
                    return redirect('add-notice-period')->with('success','Updated successfuly');
                }else{
                    $noticeMaster = new NoticeMaster();
                    $noticeMaster->orgnization_id = Auth::user()->id;
                    $noticeMaster->office_id = $request->office_id;
                    $noticeMaster->department_id = $request->department_id;
                    $noticeMaster->position_id = $request->position_id;
                    $noticeMaster->notice_days = $request->notice_days.' '.$days;
                    $noticeMaster->save();
                    return redirect('add-notice-period')->with('success','Saved successfuly');
                }
            } else{
                return redirect('add-notice-period')->with('error','Notice Days Already Exist');
            }
        }
        return view('organization.master.add_notice_period',compact('organisation','update','office','department','position'));
    }
    public function AddEducation(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $update=[];
        $department=[];
        $position=[];
        if(!empty($request->segment(2))){
            $update = EducationMaster::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
            $position = PositionMaster::where('department_id',$update->department_id)->get();
        }
        
        if(!empty($request->education_title)){
            $education_name = EducationMaster::where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('position_id',$request->position_id)->where('education_title',$request->education_title)->where('orgnization_id',$user_id)->first();
            if(empty($education_name)){
                if($request->update_id>0){
                    $educationMaster = EducationMaster::where('id',$request->update_id)->first();
                    $educationMaster->orgnization_id = Auth::user()->id;
                    $educationMaster->office_id = $request->office_id;
                    $educationMaster->department_id = $request->department_id;
                    $educationMaster->position_id = $request->position_id;
                    $educationMaster->education_title = $request->education_title;
                    $educationMaster->save();
                    return redirect('add-educations')->with('success','Updated successfuly');
                }else{
                    $educationMaster = new EducationMaster();
                    $educationMaster->orgnization_id = Auth::user()->id;
                    $educationMaster->office_id = $request->office_id;
                    $educationMaster->department_id = $request->department_id;
                    $educationMaster->position_id = $request->position_id;
                    $educationMaster->education_title = $request->education_title;
                    $educationMaster->save();
                    return redirect('add-educations')->with('success','Saved successfuly');
                }
            }else{
                return redirect('add-educations')->with('error','Education Title Already Exist');
            }
        }
        return view('organization.master.add_education',compact('organisation','update','office','department','position'));
    }
    public function AddProject(Request $request){
        $user_id = Auth::user()->id;
        $office = DB::select("SELECT id,office_name FROM `office_masters` WHERE orgnization_id=$user_id and status='Active'");

        $update=[];
        $department=[];
        if(!empty($request->segment(2))){
            $update = ProjectMaster::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
        }
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($request->project_name)){
            if($request->update_id>0){
                $project_name = ProjectMaster::select('id')->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('project_name',$request->project_name)->where('task_master',$request->task_master)->where('orgnization_id',$user_id)->where('start_date',$request->start_date)->where('end_date',$request->end_date)->first();
                if(empty($project_name->id)){
                    $projects = ProjectMaster::where('id',$request->update_id)->first();
                    $projects->orgnization_id = Auth::user()->id;
                    $projects->office_id = $request->office_id;
                    $projects->department_id = $request->department_id;
                    $projects->project_name = $request->project_name;
                    $projects->task_master = $request->task_master;
                    $projects->start_date = $request->start_date;
                    $projects->end_date = $request->end_date;
                    $projects->save();
                    return redirect('add-project')->with('success','Updated successfuly');
                }else{
                    return redirect('add-project')->with('error','Task Name Already Exist');
                }
            }else{
                $project_name = ProjectMaster::select('id')->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('project_name',$request->project_name)->where('task_master',$request->task_master)->where('orgnization_id',$user_id)->first();
                // dd($project_name);
                if(empty($project_name->id)){
                    $projects = new ProjectMaster();
                    $projects->orgnization_id = Auth::user()->id;
                    $projects->office_id = $request->office_id;
                    $projects->department_id = $request->department_id;
                    $projects->project_name = $request->project_name;
                    $projects->task_master = $request->task_master;
                    $projects->start_date = $request->start_date;
                    $projects->end_date = $request->end_date;
                    $projects->save();
                    return redirect('add-project')->with('success','Saved successfuly');
                }else{
                    return redirect('add-project')->with('error','Task Name Already Exist');
                }
            } 
        }
        return view('organization.master.add_project',compact('organisation','update','office','department'));
    }
    public function DeleteProject(Request $request){
        ProjectMaster::where('id',$request->segment(2))->delete();
        return redirect('add-project')->with('success', 'Deleted successfully');  
    }
    public function DeleteListLeave(Request $request){
        Leave::where('id',$request->segment(2))->delete();
        return redirect('list-leave')->with('success', 'Deleted successfully');  
    }
    public function DeleteEducation(Request $request){
        EducationMaster::where('id',$request->segment(2))->delete();
        return redirect('add-educations')->with('success', 'Deleted successfully');  
    }
    public function DeleteNoticePeriod(Request $request){
        NoticeMaster::where('id',$request->segment(2))->delete();
        return redirect('add-notice-period')->with('success', 'Deleted successfully');  
    }
    public function DeletePosition(Request $request){
        PositionMaster::where('id',$request->segment(2))->delete();
        return redirect('add-position')->with('success', 'Deleted successfully');  
    }
    public function DeleteSource(Request $request){
        SourceMaster::where('id',$request->segment(2))->delete();
        return redirect('add-source')->with('success', 'Deleted successfully');  
    }
    public function DeleteBank(Request $request){
        BankMaster::where('id',$request->segment(2))->delete();
        return redirect('bank-master')->with('success', 'Deleted successfully');  
    }
    public function SendRegisterMail($data){
        $email = $data->email;
        try {
            $orgnisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
            $template_data = ['email' => $data->email, 'name' => $data->first_name.' '.$data->last_name,'password'=>$data->password,'user_name'=>$orgnisation->user_name];
            Mail::send(['html'=>'email.account_registration'], $template_data,
                function ($message) use ($email) {
                    $message->to($email)->from('test8896130379@gmail.com')->subject('Account registration');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function EmployeeMaster(Request $request){
        // print_r($_POST);die;
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($_POST)){
            $users = new User();
            $users->name = $request->first_name.' '.$request->last_name;
            $users->email = $request->email;
            $users->type = 2;
            $users->password = Hash::make($request->password);
            $users->status = $request->status;
            $users->save();
            $this->SendRegisterMail($request);
            return redirect('employee-master')->with('success','Saved successfuly');
        }
        return view('organization.master.add_employee_master',compact('organisation'));
    }
    public function formCategoryMaster(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){
            $update = FormEngineCategory::where('id',$request->segment(2))->first();
        }
        if(!empty($request->name)){
            $form_name = FormEngineCategory::where('name', '=', $request->name)->where('orgnization_id',$user_id)->first();
            if(empty($form_name)){
                if($request->update_id>0){
                    $formEngineCategory = FormEngineCategory::where('id',$request->update_id)->first();
                    $formEngineCategory->orgnization_id = $user_id;
                    $formEngineCategory->name = $request->name;
                    // $formEngineCategory->is_multiple = $request->is_multiple;
                    $formEngineCategory->save();
                }else{
                    $formEngineCategory = new FormEngineCategory();
                    $formEngineCategory->orgnization_id = $user_id;
                    $formEngineCategory->name = $request->name;
                    // $formEngineCategory->is_multiple = $request->is_multiple;
                    $formEngineCategory->save();
                    return redirect('form-category-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('form-category-master')->with('error','This Form Name Already Exist');
            }
        }
        return view('organization.master.form_eng_cate_master',compact('organisation','update'));
    }
/*    public function BankMaster(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){
            $update = BankMaster::where('id',$request->segment(2))->first();
        }
        $result = BankMaster::where('orgnization_id',$user_id)->orderBy('id','DESC')->get();
        if(!empty($request->name)){
            $form_name = BankMaster::where('name','=',$request->name)->where('orgnization_id',$user_id)->first();
            if(empty($form_name)){
                if($request->update_id>0){
                    $bankmaster = BankMaster::where('id',$request->update_id)->first();
                    $bankmaster->orgnization_id = $user_id;
                    $bankmaster->name = $request->name;
                    $bankmaster->save();
                    return redirect('bank-master')->with('success','Updated successfuly');
                }else{
                    $bankmaster = new BankMaster();
                    $bankmaster->orgnization_id = $user_id;
                    $bankmaster->name = $request->name;
                    $bankmaster->save();
                    return redirect('bank-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('bank-master')->with('error','This Bank Name Already Exist');
            }
        }
        return view('organization.master.bank',compact('organisation','update','result'));
    }
*/


public function BankMaster(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){
            $update = BankMaster::where('id',$request->segment(2))->first();
        }
        $result = BankMaster::where('orgnization_id',$user_id)->orderBy('id','DESC')->get();
        if(!empty($request->name)){
            $form_name = BankMaster::where('name','=',$request->name)->where('orgnization_id',$user_id)->first();
            if(empty($form_name)){
                if($request->update_id>0){
                    $bankmaster = BankMaster::where('id',$request->update_id)->first();
                    $bankmaster->orgnization_id = $user_id;
                    $bankmaster->name = $request->name;
                    $bankmaster->save();
                    return redirect('bank-master')->with('success','Updated successfuly');
                }else{
                    $bankmaster = new BankMaster();
                    $bankmaster->orgnization_id = $user_id;
                    $bankmaster->name = $request->name;
                    $bankmaster->save();
                    return redirect('bank-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('bank-master')->with('error','This Bank Name Already Exist');
            }
        }
        return view('organization.master.bank',compact('organisation','update','result'));
    }


    public function deleteFormCategoryMaster(Request $request){
        FormEngineCategory::where('id',$request->segment(2))->delete();
        return redirect('form-category-master')->with('success', 'Deleted successfully');  
    }
    public function officeMaster(Request $request){
        $country = Country::select(['id','name'])->orderBy('name', 'ASC')->get();
        $user_id = Auth::user()->id;
        $update=[];
        $state=[];
        $city=[];
        if(!empty($request->segment(2))){
            $update = OfficeMaster::where('id',$request->segment(2))->first();
            $state = State::where('country_id',$update->country_id)->get();
            $city = City::where('state_id',$update->state_id)->get();
        }
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($request->office_name)){
            $office_check = OfficeMaster::where('office_name',$request->office_name)->where('country_id',$request->country_id)->where('city_id',$request->city_id)->where('pincode',$request->pincode)->where('address', 'like', '%' .$request->address. '%')->where('orgnization_id',$user_id)->first();
            if(empty($office_check)){
                if($request->update_id>0){
                    $officeMaster = OfficeMaster::where('id',$request->update_id)->first();
                    $officeMaster->orgnization_id = Auth::user()->id;
                    $officeMaster->office_name = $request->office_name;
                    $officeMaster->country_id = $request->country_id;
                    $officeMaster->state_id = $request->state_id;
                    $officeMaster->city_id = $request->city_id;
                    $officeMaster->pincode = $request->pincode;
                    $officeMaster->address = $request->address;
                    $officeMaster->save();
                    return redirect('office-master')->with('success','Updated successfuly');
                }else{
                    $officeMaster = new OfficeMaster();
                    $officeMaster->orgnization_id = Auth::user()->id;
                    $officeMaster->office_name = $request->office_name;
                    $officeMaster->country_id = $request->country_id;
                    $officeMaster->state_id = $request->state_id;
                    $officeMaster->city_id = $request->city_id;
                    $officeMaster->pincode = $request->pincode;
                    $officeMaster->address = $request->address;
                    $officeMaster->save();
                    return redirect('office-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('office-master')->with('error','Office Name and Address Already Exist');
            }
        }
        return view('organization.master.add_office',compact('organisation','update','country','state','city'));
    }
    public function deleteOfficeMaster(Request $request){
        OfficeMaster::where('id',$request->segment(2))->delete();
        return redirect('office-master')->with('success', 'Deleted successfully');  
    }
/*    public function departmentMaster(Request $request){
        $user_id = Auth::user()->id;
        $office = OfficeMaster::select('id','office_name','address','status')->where('status','Active')->where('orgnization_id',$user_id)->orderBy('office_name', 'ASC')->get();
        $results = DB::select("SELECT a.id,a.department_name,a.parent_id,a.status,a.created_at,a.updated_at,a.sub_department,a.type_of_department,b.office_name FROM `department_masters` AS a INNER JOIN office_masters AS b ON a.office_id=b.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        $update=[];
        $departments=[];
        if(!empty($request->segment(2))){
            $update = DepartmentMaster::where('id',$request->segment(2))->first();
            $departments = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$update->office_id)->where('type_of_department',0)->get();
        }
        $organisation = $this->GetOrganisation($user_id);
        return view('organization.master.add_department',compact('organisation','update','office','departments','results'));
    }*/


        public function departmentMaster(Request $request){
        $user_id = Auth::user()->id;
        $office = OfficeMaster::select('id','office_name','address','status')->where('status','Active')->where('orgnization_id',$user_id)->orderBy('office_name', 'ASC')->get();
        $results = DB::select("SELECT a.id,a.department_name,a.parent_id,a.status,a.created_at,a.updated_at,a.sub_department,a.type_of_department,b.office_name FROM `department_masters` AS a INNER JOIN office_masters AS b ON a.office_id=b.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        $update=[];
        $departments=[];
        if(!empty($request->segment(2))){
            $update = DepartmentMaster::where('id',$request->segment(2))->first();
            $departments = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$update->office_id)->get();
        }
        $organisation = $this->GetOrganisation($user_id);
        if(!empty($request->department_name)){
        
            if($request->upd_id>0){
          
               $depart = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->where('id', '!=', $request->upd_id)->first();
                $depart1 = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('id', '!=', $request->upd_id)->first();
            } else {
              
            $depart = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->first();
            $depart1 = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->first();
            
            }
            
            $department = '';

            if(!empty($depart)){
                $department = $depart;
            }elseif(!empty($depart1)){
                $department = $depart1;
            }

            if(empty($department)){
                if($request->upd_id>0){
                   // dd($request->type_of_department);
                    if($request->type_of_department == 'department_id'){
                        $parent_id = $request->parent_id;
                    } else {
                        $parent_id = '';
                    }
                    

                    $departmentMaster = DepartmentMaster::where('id',$request->upd_id)->first();
                    $departmentMaster->orgnization_id = Auth::user()->id;
                    $departmentMaster->office_id = $request->office_id;
                    $departmentMaster->department_name = $request->department_name;
                    if($request->type_of_department=='department_id'){
                        $slect = DepartmentMaster::select('department_name')->where('id',$request->parent_id)->first();
                        $departmentMaster->type_of_department=1;
                        $departmentMaster->parent_id = $parent_id;
                        $departmentMaster->sub_department = $slect->department_name;
                    }else{
                        $departmentMaster->parent_id=0;
                        $departmentMaster->type_of_department=0;
                    }
                    $departmentMaster->save();
                    return redirect('department-master')->with('success','Updated successfuly');
                }else{
                    $departmentMaster = new DepartmentMaster();
                    $departmentMaster->orgnization_id = Auth::user()->id;
                    $departmentMaster->office_id = $request->office_id;
                    $departmentMaster->department_name = $request->department_name;
                    if($request->type_of_department=='department_id'){
                        $slect = DepartmentMaster::select('department_name')->where('id',$request->parent_id)->first();
                        $departmentMaster->type_of_department=1;
                        $departmentMaster->parent_id = $request->parent_id;
                        $departmentMaster->sub_department = $slect->department_name;
                    }else{
                        $departmentMaster->parent_id=0;
                    }
                    $departmentMaster->save();
                    return redirect('department-master')->with('success','Saved successfuly');
                }
            } else{
                return redirect('department-master')->with('error','Department Name Already Exist');
            }
        }
        return view('organization.master.add_department',compact('organisation','update','office','departments','results'));
    }


    public function SaveDepartmentMaster(Request $request){
        $user_id = Auth::user()->id;
        $select = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->first();
        if(!empty($select)){
            return redirect('department-master')->with('error','Department already existed');
        }
        $select = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->first();
        if(!empty($select)){
            return redirect('department-master')->with('error','Department already existed');
        }
        $departmentMaster = new DepartmentMaster();
        $departmentMaster->orgnization_id = $user_id;
        $departmentMaster->office_id = $request->office_id;
        $departmentMaster->department_name = $request->department_name;
        if($request->type_of_department=='department_id'){
            $slect = DepartmentMaster::select('department_name')->where('id',$request->parent_id)->first();
            $departmentMaster->type_of_department=1;
            $departmentMaster->parent_id = $request->parent_id;
            $departmentMaster->sub_department = $slect->department_name;
        }else{
            $departmentMaster->parent_id=0;
        }
        $departmentMaster->save();
        return redirect('department-master')->with('success','Saved successfuly');
    }
    
    public function UpdateDepartmentMaster(Request $request){
        $user_id = Auth::user()->id;
        if($request->type_of_department=='department_id'){
            if(!empty(DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->where('parent_id',$request->parent_id)->first())){
                return redirect('department-master')->with('error','Department already existed');
            }
            $department = DepartmentMaster::select('id')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->first();
            if($department->type_of_department==1){
                $departmentMaster = DepartmentMaster::where('id',$request->upd_id)->first();
                $departmentMaster->orgnization_id = Auth::user()->id;
                $departmentMaster->office_id = $request->office_id;
                $departmentMaster->department_name = $request->department_name;
                $departmentMaster->type_of_department = 0;
                $departmentMaster->parent_id = 0;
                $departmentMaster->sub_department = null;
                $departmentMaster->save();
                return redirect('department-master')->with('success','Updated successfuly');
            }else{
                $departmentMaster = DepartmentMaster::where('id',$request->upd_id)->first();
                $departmentMaster->orgnization_id = $user_id;
                $departmentMaster->office_id = $request->office_id;
                $departmentMaster->department_name = $request->department_name;
                if($request->type_of_department=='department_id'){
                    $slect = DepartmentMaster::select('department_name')->where('id',$request->parent_id)->first();
                    $departmentMaster->type_of_department=1;
                    $departmentMaster->parent_id = $request->parent_id;
                    $departmentMaster->sub_department = $slect->department_name;
                }else{
                    $departmentMaster->parent_id=0;
                }
                $departmentMaster->save();
                return redirect('department-master')->with('success','Updated successfuly');
            }
        }else{
            $department = DepartmentMaster::select('id','type_of_department')->where('department_name',$request->department_name)->where('office_id',$request->office_id)->where('orgnization_id',$user_id)->first();
            if(!empty($department)){
                $departmentMaster = DepartmentMaster::where('id',$request->upd_id)->first();
                $departmentMaster->orgnization_id = Auth::user()->id;
                $departmentMaster->office_id = $request->office_id;
                $departmentMaster->department_name = $request->department_name;
                $departmentMaster->type_of_department = 0;
                $departmentMaster->parent_id = 0;
                $departmentMaster->sub_department = null;
                $departmentMaster->save();
                return redirect('department-master')->with('success','Saved successfuly');
            }else{
                $departmentMaster = DepartmentMaster::where('id',$request->upd_id)->first();
                $departmentMaster->orgnization_id = $user_id;
                $departmentMaster->office_id = $request->office_id;
                $departmentMaster->department_name = $request->department_name;
                if($request->type_of_department=='department_id'){
                    $slect = DepartmentMaster::select('department_name')->where('id',$request->parent_id)->first();
                    $departmentMaster->type_of_department=1;
                    $departmentMaster->parent_id = $request->parent_id;
                    $departmentMaster->sub_department = $slect->department_name;
                }else{
                    $departmentMaster->parent_id=0;
                }
                $departmentMaster->save();
            }
        }
    }
    public function deleteDepartmentMaster(Request $request){
        DepartmentMaster::where('id',$request->segment(2))->delete();
        return redirect('department-master')->with('success', 'Deleted successfully');  
    }
    public function AddShift(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        $shift_update= '';
        if($request->edit_id){
            $type = $request->edit_id;
        } else {
            $type = 0; 
        }

       // dd($request);
        

        if(!empty($request->segment(2))){
            $update = ShiftMaster::where('id',$request->segment(2))->first();
            $shift_update = ShiftDuration::where('shift_id', $update->id)->first();
            
        } else {
            if($type != 0){
            $update = ShiftMaster::where('id',$type)->first();
            $shift_update = ShiftDuration::where('shift_id', @$update->id)->first();
        }  
         }

        $data_days=WeekDay::get();
        if(!empty($request->shift_name) && !empty($request->shift_type)){
            if($request->shift_type!='Flexible'){
                $count = count($request->break_start_time);
            }
             
            if($type == 0) {
                $shiftDuration = new ShiftMaster();
                $shiftDuration->orgnization_id = $user_id;
                $shiftDuration->shift_type = $request->shift_type;
                $shiftDuration->shift_name = $request->shift_name;
            if($request->days){
            $counts = count($request->days);
            } else {
            $counts = 0;    
            }
            $datax = [];
            for($j=0;$j < $counts;$j++){
                $days = $request->days[$j];
                $datas=[
                    $days=>$_POST[$days]
                ];
                $datax[]=$datas;
            }
            $shiftDuration->holidays = json_encode($datax);
            $shiftDuration->save();

            } else {

            if($request->days){
            $counts = count($request->days);
            } else {
            $counts = 0;    
            }
            $datax = [];
            for($j=0;$j < $counts;$j++){
                $days = $request->days[$j];
                $datas=[
                    $days=>$_POST[$days]
                ];
                $datax[]=$datas;
            } 

            ShiftMaster::where('id', $type)->update([
                'orgnization_id' => $user_id,
                'shift_type'     => $request->shift_type,
                'shift_name'     => $request->shift_name,
                'holidays'       => json_encode($datax),
            ]);

            }

            $sr=1;
            if($request->shift_type=='Flexible'){
                    $shift_master = new ShiftDuration();
                    $shift_master->orgnization_id = $user_id;
                    $shift_master->shift_id = $shiftDuration->id;
                    $shift_master->type_of_shift = $_POST['type_of_shift1'];
                    $shift_master->shift_duration = @$request->shift_duration;
                    $shift_master->out_time = @$request->out_time;
                    $shift_master->min_present_duration = @$request->min_present_duration;
                    if(!empty($request->enable_half_day)){
                        $shift_master->max_present_duration = @$request->min_present_duration;
                    }
                    $shift_master->continuous_double_shift = @$request->continuous_double_shift;
                    $shift_master->enable_half_day = @$request->enable_half_day;
                    $shift_master->save();
            }else{
                if(!empty($count)){
                    for($i=0;$i<$count;$i++){

                       if($type == 0) {

                        $shift_master = new ShiftDuration();
                        $shift_master->orgnization_id = $user_id;
                        $shift_master->shift_id = @$shiftDuration->id;
                        $shift_master->type_of_shift = $_POST['type_of_shift'.$sr++][$i];
                        $shift_master->in_time = @$request->in_time[$i];
                        $shift_master->out_time = @$request->out_time[$i];
                        $shift_master->break_start_time = @$request->break_start_time[$i];
                        $shift_master->break_end_time = @$request->break_end_time[$i];
                        $shift_master->in_time_relaxation = @$request->in_time_relaxation[$i];
                        $shift_master->out_time_relaxation = @$request->out_time_relaxation[$i];
                        $shift_master->min_present_duration = @$request->min_present_duration[$i];
                        if(!empty($request->enable_half_day[$i])){
                            $shift_master->max_present_duration = @$request->min_present_duration[$i];
                        }
                        if(!empty($request->enable_half_day[$i])){
                            $shift_master->enable_half_day = @$request->enable_half_day[$i];
                        }
                        if(!empty($request->continuous_double_shift[$i])){
                            $shift_master->variable_shift = @$request->variable_shift[$i];
                        }
                        $shift_master->save();

                        } else {
                            $max_present_duration = null;
                            if(!empty($request->enable_half_day[$i])){
                            $max_present_duration = @$request->min_present_duration[$i];
                            }
                            
                            $enable_half_day = null;
                            if(!empty($request->enable_half_day[$i])){
                                $enable_half_day = @$request->enable_half_day[$i];
                            }
                            $variable_shift = null; 
                            if(!empty($request->continuous_double_shift[$i])){
                             $variable_shift = @$request->variable_shift[$i];
                            }

                            //dd($shift_update->id);

                            ShiftDuration::where('id', $shift_update->id)->update([
                                'orgnization_id' => $user_id,
                                'shift_id'       => $update->id,
                                'type_of_shift'  => $_POST['type_of_shift'.$sr++][$i],
                                'in_time'        => @$request->in_time[$i],
                                'out_time'        => @$request->out_time[$i],
                                'break_start_time' => @$request->break_start_time[$i],
                                'break_end_time'   => @$request->break_end_time[$i],
                                'in_time_relaxation'  => @$request->in_time_relaxation[$i],
                                'out_time_relaxation' => @$request->out_time_relaxation[$i],
                                'min_present_duration' => @$request->min_present_duration[$i],
                                'max_present_duration' => $max_present_duration,
                                'enable_half_day' => $enable_half_day,
                                'variable_shift' => $variable_shift,
                            ]);


                        }


                    }
                }
            }
            return redirect('shift-details')->with('success','Saved successfuly');
        }
        return view('organization.master.shift_management',compact('organisation','update','data_days','shift_update'));
    }
    public function ShiftDetails(){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $result = ShiftMaster::where('orgnization_id',$user_id)->orderBy('id','DESC')->get();
        return view('organization.master.shift_details',compact('organisation','result'));
    }
    public function deleteShiftMaster(Request $request){
        ShiftMaster::where('id',$request->segment(2))->delete();
        return redirect('shift-details')->with('success', 'Deleted successfully');  
    }
    public function addManualMarkAttendance(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $employee_name = DB::select("SELECT id,name FROM `users` WHERE type='2'");
        $office_name = DB::select("SELECT id,office_name FROM `office_masters` WHERE orgnization_id=$user_id");
        $department_name = DB::select("SELECT id,department_name FROM `department_masters` WHERE orgnization_id=$user_id");
        
        // $employee_atten_details = DB::select("SELECT a.user_id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `emp_details` as a LEFT JOIN emp_attendances as b on a.user_id=b.user_id INNER JOIN users as c on c.id=a.user_id WHERE a.created_by=$user_id AND c.type=2 GROUP by a.id ORDER BY name ASC;");
        
        
        if(!empty($request->emp_id)){
                $empAttendance = new EmpAttendance();
                $empAttendance->orgnization_id = $user_id;
                $empAttendance->user_id = $request->emp_id;
                $empAttendance->office_id = $request->office_id;
                $empAttendance->department_id = $request->department_id;
                $empAttendance->start_date = $request->start_date;
                $empAttendance->end_date = $request->end_date;
                $empAttendance->in_time = $request->in_time;
                $empAttendance->out_time = $request->out_time;
                $empAttendance->save();
                return redirect('add-manual-mark-attendance')->with('success','Saved successfuly');
        }

        return view('organization.employee.add_manual_mark_attendance',compact('organisation','employee_name','office_name','department_name'));  
    }
    public function manualMarkAttendance(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $employee_name = DB::select("SELECT id,name FROM `users` WHERE type='2'");
        $office_name = DB::select("SELECT id,office_name FROM `office_masters` WHERE orgnization_id=$user_id");
        $department_name = DB::select("SELECT id,department_name FROM `department_masters` WHERE orgnization_id=$user_id");

        // $employee_atten_details = DB::select("SELECT a.id,a.in_time,a.out_time,a.start_date,a.end_date,b.office_name,c.department_name FROM `emp_attendances` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id WHERE a.orgnization_id=$user_id ORDER BY name ASC;");
        
        return view('organization.employee.manual_mark_attendance',compact('organisation','employee_name','office_name','department_name'));
    }
    public function addMissedPunch(Request $request){
        $user_id = Auth::user()->id;
        if($request->end_date){
            $empAttendance = EmpAttendance::where('id',$request->get_id)->where('orgnization_id',$user_id)->first();
            $empAttendance->end_date = $request->end_date;
            $empAttendance->out_time = $request->out_time;
            $empAttendance->save();
        }
    }
    public function LeaveMaster(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $employee_type = EmpType::select('id','emp_type')->where('orgnization_id',$user_id)->orderBy('emp_type', 'ASC')->get();
        $update=[];
        $department=[];
        if(!empty($request->segment(2))){
            $update = LeaveType::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
        }
        if(!empty($request->name)){
            $leaveName = LeaveType::where('name', '=', $request->name)->where('department_id', '=', $request->department_id)->where('office_id',$request->office_id)->where('emp_type',$request->emp_type)->where('total_leave',$request->total_leave)->where('orgnization_id',$user_id)->first();
            if(empty($leaveName)){
                if($request->update_id>0){
                    $leaveMaster = LeaveType::where('id',$request->update_id)->first();
                    $leaveMaster->orgnization_id = Auth::user()->id;
                    $leaveMaster->name = $request->name;
                    $leaveMaster->office_id = $request->office_id;
                    $leaveMaster->department_id = $request->department_id;
                    $leaveMaster->emp_type = $request->emp_type;
                    $leaveMaster->total_leave = $request->total_leave;
                    $leaveMaster->save();
                    return redirect('leave-master')->with('success','Updated successfuly');
                }else{
                    $leaveMaster = new LeaveType();
                    $leaveMaster->orgnization_id = Auth::user()->id;
                    $leaveMaster->name = $request->name;
                    $leaveMaster->office_id = $request->office_id;
                    $leaveMaster->department_id = $request->department_id;
                    $leaveMaster->emp_type = $request->emp_type;
                    $leaveMaster->total_leave = $request->total_leave;
                    $leaveMaster->save();
                    return redirect('leave-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('leave-master')->with('error','Leave Name Already Exist');
            }
        }
        return view('organization.master.leave_master',compact('organisation','update','office','department','employee_type'));
    }
    public function deleteLeaveMaster(Request $request){
        LeaveType::where('id',$request->segment(2))->delete();
        return redirect('leave-master')->with('success', 'Deleted successfully');  
    }
    public function empTypeMaster(Request $request){
        $user_id = Auth::user()->id;
        $update=[];
        $organisation = $this->GetOrganisation($user_id);
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        if(!empty($request->segment(2))){
            $update = EmpType::where('id',$request->segment(2))->first();
        }
        if(!empty($request->emp_type)){
            $empType = EmpType::where('office_id',$request->office_id)->where('emp_type',$request->emp_type)->where('orgnization_id',$user_id)->first();
            if(empty($empType)){
                if($request->update_id>0){
                    $empType = EmpType::where('id',$request->update_id)->first();;
                    $empType->orgnization_id = Auth::user()->id;
                    $empType->office_id = $request->office_id;
                    $empType->emp_type = $request->emp_type;
                    $empType->save();
                    return redirect('emp-type-master')->with('success','Updated successfuly');
                }else{
                    $empType = new EmpType();
                    $empType->orgnization_id = Auth::user()->id;
                    $empType->office_id = $request->office_id;
                    $empType->emp_type = $request->emp_type;
                    $empType->save();
                    return redirect('emp-type-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('emp-type-master')->with('error','Employee Type Name Already Exist');
            }
        }

        return view('organization.master.employee_type_master',compact('organisation','office','update'));
    }
    public function deleteEmpTypeMaster(Request $request){
        EmpType::where('id',$request->segment(2))->delete();
        return redirect('emp-type-master')->with('success', 'Deleted successfully');  
    }
    public function AddAssignTask(Request $request){
        $user_id = Auth::user()->id;
        $office = DB::select("SELECT id,office_name FROM `office_masters` WHERE orgnization_id=$user_id and status='Active'");
        $update=[];
        $department=[];
        $activity_name=[];
        $task_name=[];
        $organisation = $this->GetOrganisation($user_id);

        // $task_name = ProjectMaster::select('id','project_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('project_name', 'ASC')->get();

        // $activity_name = DB::select("SELECT a.id,a.project_id,a.activity_name FROM `project_activities` AS a INNER JOIN project_masters AS b ON a.project_id=b.id WHERE b.orgnization_id=$user_id");

        $user_name = DB::select("SELECT a.id,a.user_id,b.name FROM `employee_infos` AS a INNER JOIN users AS b ON a.user_id=b.id WHERE a.organisation_id=$user_id GROUP BY a.user_id");
    
        if(!empty($request->segment(2))){
            $update = AssignTask::where('id',$request->segment(2))->first();
            $activity_name = ProjectActivity::select('id','activity_name')->where('project_id',$update->project_id)->get();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
            $task_name = ProjectMaster::where('department_id',$update->department_id)->get();
        }
    
        if(!empty($request->activity_id)){
            if($request->update_id>0){
                $assignTask = AssignTask::select('id')->where('activity_id',$request->activity_id)->where('project_id',$request->project_id)->where('user_id',$request->user_id)->where('status',$request->status)->where('message',$request->message)->where('orgnization_id',$user_id)->first();
                if(empty($assignTask->id)){
                    for($i=0; $i<count($request->activity_id); $i++){
                        $assignTask = AssignTask::where('id',$request->update_id)->first();
                        $assignTask->orgnization_id = Auth::user()->id;
                        $assignTask->activity_id = $request->activity_id[$i];
                        $assignTask->project_id = $request->project_id;
                        $assignTask->office_id = $request->office_id;
                        $assignTask->department_id = $request->department_id;
                        $assignTask->user_id = $request->user_id;
                        $assignTask->status = $request->status;
                        $assignTask->message = $request->message;
                        $assignTask->save();
                    }
                    return redirect('add-assign-task')->with('success','Saved successfuly');
                }else{
                    return redirect('add-assign-task')->with('error','Task Name Already Exist');
                }
            }else{
                $assignTask = AssignTask::select('id')->where('activity_id',$request->activity_id)->where('project_id',$request->project_id)->where('user_id',$request->user_id)->where('orgnization_id',$user_id)->first();
    
                if(empty($assignTask->id)){
                    for($i=0; $i<count($request->activity_id); $i++){
                        $assignTask = new AssignTask();
                        $assignTask->orgnization_id = Auth::user()->id;
                        $assignTask->activity_id = $request->activity_id[$i];
                        $assignTask->project_id = $request->project_id;
                        $assignTask->office_id = $request->office_id;
                        $assignTask->department_id = $request->department_id;
                        $assignTask->user_id = $request->user_id;
                        $assignTask->status = $request->status;
                        $assignTask->message = $request->message;
                        $assignTask->save();
                    }
                    return redirect('add-assign-task')->with('success','Saved successfuly');
                }else{
                    return redirect('add-assign-task')->with('error','Task Name Already Exist');
                }
            }
        }
        return view('organization.task_master.add_assign_task',compact('organisation','office','department','task_name','activity_name','user_name','update'));
    }

    public function DeleteAssignTask(Request $request){
        AssignTask::where('id',$request->segment(2))->delete();
        return redirect('add-assign-task')->with('success', 'Deleted successfully');  
    }
    public function TemplateMaster(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        /*$get_email_data = DB::table('template_masters')->where('orgnization_id', $user_id)->first();
        $get_sms_data = DB::table('template_masters')->where('orgnization_id', $user_id)->first();
        $get_notification_data = DB::table('template_masters')->where('orgnization_id', $user_id)->first();

        return view('organization.master.template_master',compact('organisation', 'get_email_data', 'get_sms_data','get_notification_data'));*/


        $office= OfficeMaster::select('id','office_name','status')->where('status','Active')->where('orgnization_id',$user_id)->orderBy('id', 'ASC')->get();
       foreach($office as $ofc_data){
         $templatemasterRecord= TemplateMaster::select('id','office_id','office_name','status')->where('office_id',$ofc_data->id)->where('orgnization_id',$user_id)->orderBy('office_id', 'ASC')->get();
            $rec_data=count($templatemasterRecord); 
              if($rec_data=='0') { 
                        $savetemplateMaster = new TemplateMaster();
                        $savetemplateMaster->orgnization_id = Auth::user()->id;
                        $savetemplateMaster->office_id = $ofc_data->id;
                        $savetemplateMaster->office_name = $ofc_data->office_name;
                        $savetemplateMaster->email_template = '0';
                        $savetemplateMaster->sms_template = '0';
                        $savetemplateMaster->notification_template = '0';
                        $savetemplateMaster->status = $ofc_data->status;
                        $savetemplateMaster->save();
                }
        } 

      //$template_masters= TemplateMaster::select('id','office_id','office_name','email_template','sms_template','notification_template','status','created_at','updated_at','status')->where('orgnization_id',$user_id)->orderBy('office_id', 'DESC')->get();


        $template_masters=DB::select("SELECT a.id,a.orgnization_id,a.office_id,b.office_name,a.email_template,a.sms_template,a.notification_template FROM `template_masters` AS a INNER JOIN `office_masters` AS b ON a.office_id=b.id WHERE a.orgnization_id=$user_id and b.status='Active' ORDER BY a.id DESC");
        
        $get_email_data = DB::table('template_masters')->where('orgnization_id', $user_id)->first();
        $get_sms_data = DB::table('template_masters')->where('orgnization_id', $user_id)->first();
        $get_notification_data = DB::table('template_masters')->where('orgnization_id', $user_id)->first();

        return view('organization.master.template_master',compact('organisation', 'get_email_data', 'get_sms_data','get_notification_data','template_masters'));

    }

  /*  public function headerFooterTemplateMaster(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        return view('organization.master.header_footer_template_master',compact('organisation'));
    }*/
    
    public function HeaderFooterTemplateMaster(Request $request){ 
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){ 
            $update = HeaderFooterTemplateMaster::where('id',$request->segment(2))->first();
        }

        $office = OfficeMaster::select('id','office_name','status')->where('status','Active')->where('orgnization_id',$user_id)->orderBy('office_name', 'ASC')->get();
        $results=DB::select("SELECT a.id,a.orgnization_id,b.office_name,a.header_image,a.footer_image,a.status,a.created_at,a.updated_at FROM `header_footer_template_masters` AS a INNER JOIN `office_masters` AS b ON a.office_id=b.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");     
        $rand_num=rand(0,987654323);
        if(!empty($request->office_id)){
            $hftMaster = HeaderFooterTemplateMaster::where('office_id', '=', $request->office_id)->where('orgnization_id',$user_id)->first();
            if(empty($hftMaster)){
                if($request->update_id>0){
                    $hftMaster = HeaderFooterTemplateMaster::where('id',$request->update_id)->first();
                    $hftMaster->orgnization_id = Auth::user()->id;
                    $hftMaster->office_id = $request->office_id;

                    if(!empty($request->header_image)){
                        $HeaderimgfileName = strtolower($request->office_id).'_'.$rand_num.'.'.$request->header_image->extension();
                        $request->header_image->move(public_path('organization/header_image'),$HeaderimgfileName);
                        $hftMaster->header_image = $HeaderimgfileName;
                    }    
                   
                     if(!empty($request->header_image)) {
                        $FooterimgfileName = strtolower($rand_num).'_'.$user_id.'.'.$request->footer_image->extension();
                        $request->footer_image->move(public_path('organization/footer_image'),$FooterimgfileName);
                        $hftMaster->footer_image = $FooterimgfileName;
                    }

                    $hftMaster->save();
                    return redirect('header-footer-template-master')->with('success','Updated successfuly');
                }else{
                    $hftMaster = new HeaderFooterTemplateMaster();
                    $hftMaster->orgnization_id = Auth::user()->id;
                    $hftMaster->office_id = $request->office_id;
                    $HeaderimgfileName = strtolower($request->office_id).'_'.$rand_num.'.'.$request->header_image->extension();
                    $request->header_image->move(public_path('organization/header_image'),$HeaderimgfileName);
                    $hftMaster->header_image = $HeaderimgfileName;
                    $FooterimgfileName = strtolower($rand_num).'_'.$user_id.'.'.$request->footer_image->extension();
                    $request->footer_image->move(public_path('organization/footer_image'),$FooterimgfileName);
                    $hftMaster->footer_image = $FooterimgfileName;
                    $hftMaster->save();
                    return redirect('header-footer-template-master')->with('success','Saved successfuly');
                }
            }else{
                return redirect('header-footer-template-master')->with('error','This Header Record Already Exist');
            }
        }

        return view('organization.master.header_footer_template_master',compact('organisation','office','results','update'));
    }
    public function deleteHeaderFooterTemplateMaster(Request $request){
        HeaderFooterTemplateMaster::where('id',$request->segment(2))->delete();
        return redirect('header-footer-template-master')->with('success', 'Deleted successfully');  
    }


}