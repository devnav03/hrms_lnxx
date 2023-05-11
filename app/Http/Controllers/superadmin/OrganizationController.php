<?php

namespace App\Http\Controllers\superadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Mail;
class OrganizationController extends Controller
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

    public function organizationUpdate(Request $request){
        $inputs = $request->all();
 
        if(!empty($request->company_name) && !empty($request->email)){
            $org = Organisation::where('user_id', $request->up_id)->select('id', 'logo', 'user_name')->first();

            $fileName = $org->logo;
            if(isset($inputs['logo']) or !empty($inputs['logo'])) {
            $fileName = strtolower($org->user_name).'.'.$request->logo->extension();
            $request->logo->move(public_path('organization/logo'), $fileName);
            }
            
            Organisation::where('id', $org->id)->update(['company_name' => $request->company_name, 'address' => $request->address, 'logo' => $fileName ]); 

            User::where('id', $request->up_id)->update(['name'  => $request->company_name, 'email' => $request->email, 'mobile' => $request->mobile, 'status' => $request->status ]); 

            return redirect('add-organization')->with('success','Update successfuly');
        }

        return view('superadmin.add_organization');

    }


    public function AddOrganization(Request $request){
        $inputs = $request->all();
        if(!empty($request->company_name) && !empty($request->email) && !empty($request->user_name)){
            $users = new User();
            $users->name = $request->company_name;
            $users->email = $request->email;
            $users->mobile = $request->mobile;
            $users->status = $request->status;
            $users->type = 1;
            if(!empty($request->active_notification)){
                $users->active_notification = 1;
                $this->SendRegisterMail($request);
            }
            $users->password = Hash::make($request->password);
            $users->save();

            $organisation = new Organisation();
            $organisation->user_id = $users->id;
            $organisation->user_name = strtolower($request->user_name);
            $organisation->company_name = $request->company_name;
            $organisation->address = $request->address;
            $organisation->created_by = Auth::user()->id;
            //dd($request);
            $fileName = '';
            if(isset($inputs['logo']) or !empty($inputs['logo'])) {
            $fileName = strtolower($request->user_name).'.'.$request->logo->extension();
            $request->logo->move(public_path('organization/logo'), $fileName);
            }
            $organisation->logo = $fileName;
            $organisation->save();
            return redirect('add-organization')->with('success','Saved successfuly');
        }
        return view('superadmin.add_organization');
    }
    public function SendRegisterMail($data){
        $email = $data->email;
        try {
            $template_data = ['email' => $data->email, 'name' => $data->company_name,'password'=>$data->password,'user_name'=>$data->user_name];
            Mail::send(['html'=>'email.account_registration'], $template_data,
                function ($message) use ($email) {
                    $message->to($email)->from('dipanshu.roy68@gmail.com')->subject('Account registration');
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }

    public function log_out(){
        \Auth::logout();
        \Session::flush();
        return redirect('login');
    }

    public function user_log_out(){
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->select('organisation_id')->first();
        $link = Organisation::where('user_id', $user->organisation_id)->select('user_name')->first();
        \Auth::logout();
        \Session::flush();
        return redirect($link->user_name);  

    }

    
    public function UpdateOrganization($id){
        $update = DB::select("SELECT a.id,a.name,b.company_name,b.user_name,a.email,a.mobile,b.logo,b.address,a.created_at,a.updated_at FROM users as a INNER JOIN organisations as b on a.id=b.user_id WHERE a.id=$id");
        if(!empty($update)){
            $update = $update[0];
        }
        return view('superadmin.add_organization',compact('update'));
    }

    public function DeleteOrganization($id = null){
     //   Organisation::where('id', $id)->delete();
        \DB::table('organisations')->where('id', $id)->delete();
       // dd($id);
        return redirect('add-organization')->with('success', 'Deleted successfully');
    }


}
