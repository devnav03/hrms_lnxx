<?php

namespace App\Http\Controllers\organization\settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Organisation;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\NotificationTemplate;
use DB;
class SettingController extends Controller
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

    public function GetOrganisation($user_id){
        return Organisation::where(['user_id'=>$user_id])->first();
     }

    public function addEmailTemplate(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){
            $update = EmailTemplate::where('id',$request->segment(2))->first();
        }

        if(!empty($request->template_title)){
            if($request->update_id>0){
                $emailTemplate_check = EmailTemplate::select('id')->where('template_title',$request->template_title)->where('description',$request->description)->where('orgnization_id',$user_id)->first();
                if(empty($emailTemplate_check->id)){
                    $emailTemplate = EmailTemplate::where('id',$request->update_id)->first();
                    $emailTemplate->orgnization_id = Auth::user()->id;
                    $emailTemplate->template_title = $request->template_title;
                    $emailTemplate->description = $request->description;
                    $emailTemplate->save();
                    return redirect('add-email-template')->with('success','Updated successfuly');
                }else{
                    return redirect('add-email-template')->with('error','Template Name Already Exist');
                }
            }else{
                $emailTemplate_check = EmailTemplate::select('id')->where('template_title',$request->template_title)->where('description',$request->description)->where('orgnization_id',$user_id)->first();
                if(empty($emailTemplate_check->id)){
                    $emailTemplate = new EmailTemplate();
                    $emailTemplate->orgnization_id = Auth::user()->id;
                    $emailTemplate->template_title = $request->template_title;
                    $emailTemplate->description = $request->description;
                    $emailTemplate->save();
                    return redirect('add-email-template')->with('success','Saved successfuly');
                }else{
                    return redirect('add-email-template')->with('error','Template Name Already Exist');
                }
            }   
        }   
        return view('organization.settings.add_email_template',compact('organisation','update'));
    }

    public function emailTemplateDelete(Request $request){
        EmailTemplate::where('id',$request->segment(2))->delete();
        return redirect('add-email-template')->with('success', 'Deleted successfully');  
    }
    public function addSMSTemplate(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){
            $update = SmsTemplate::where('id',$request->segment(2))->first();
        }

        if(!empty($request->template_title)){
            if($request->update_id>0){
                $smsTemplate_check = SmsTemplate::select('id')->where('template_title',$request->template_title)->where('description',$request->description)->where('orgnization_id',$user_id)->first();
                if(empty($smsTemplate_check->id)){
                    $smsTemplate = SmsTemplate::where('id',$request->update_id)->first();
                    $smsTemplate->orgnization_id = Auth::user()->id;
                    $smsTemplate->template_title = $request->template_title;
                    $smsTemplate->description = $request->description;
                    $smsTemplate->save();
                    return redirect('add-sms-template')->with('success','Updated successfuly');
                }else{
                    return redirect('add-sms-template')->with('error','Template Name Already Exist');
                }
            }else{
                $smsTemplate_check = SmsTemplate::select('id')->where('template_title',$request->template_title)->where('description',$request->description)->where('orgnization_id',$user_id)->first();
                if(empty($smsTemplate_check->id)){
                    $smsTemplate = new SmsTemplate();
                    $smsTemplate->orgnization_id = Auth::user()->id;
                    $smsTemplate->template_title = $request->template_title;
                    $smsTemplate->description = $request->description;
                    $smsTemplate->save();
                    return redirect('add-sms-template')->with('success','Saved successfuly');
                }else{
                    return redirect('add-sms-template')->with('error','Template Name Already Exist');
                }
            }   
        }   
        return view('organization.settings.add_sms_template',compact('organisation','update'));
    }
    public function addNotificationemplate(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $update=[];
        if(!empty($request->segment(2))){
            $update = NotificationTemplate::where('id',$request->segment(2))->first();
        }

        if(!empty($request->template_title)){
            if($request->update_id>0){
                $notificationTemplate_check = NotificationTemplate::select('id')->where('template_title',$request->template_title)->where('description',$request->description)->where('orgnization_id',$user_id)->first();
                if(empty($notificationTemplate_check->id)){
                    $notificationTemplate = NotificationTemplate::where('id',$request->update_id)->first();
                    $notificationTemplate->orgnization_id = Auth::user()->id;
                    $notificationTemplate->template_title = $request->template_title;
                    $notificationTemplate->description = $request->description;
                    $notificationTemplate->save();
                    return redirect('add-notification-template')->with('success','Updated successfuly');
                }else{
                    return redirect('add-notification-template')->with('error','Template Name Already Exist');
                }
            }else{
                $notificationTemplate_check = NotificationTemplate::select('id')->where('template_title',$request->template_title)->where('description',$request->description)->where('orgnization_id',$user_id)->first();
                if(empty($notificationTemplate_check->id)){
                    $notificationTemplate = new NotificationTemplate();
                    $notificationTemplate->orgnization_id = Auth::user()->id;
                    $notificationTemplate->template_title = $request->template_title;
                    $notificationTemplate->description = $request->description;
                    $notificationTemplate->save();
                    return redirect('add-notification-template')->with('success','Saved successfuly');
                }else{
                    return redirect('add-notification-template')->with('error','Template Name Already Exist');
                }
            }   
        }   
        return view('organization.settings.add_notification_template',compact('organisation','update'));
    }
    public function notificationTemplateDelete(Request $request){
        NotificationTemplate::where('id',$request->segment(2))->delete();
        return redirect('add-notification-template')->with('success', 'Deleted successfully');  
    }
    public function smsTemplateDelete(Request $request){
        SmsTemplate::where('id',$request->segment(2))->delete();
        return redirect('add-sms-template')->with('success', 'Deleted successfully');  
    }
}
