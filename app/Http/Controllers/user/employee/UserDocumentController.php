<?php

namespace App\Http\Controllers\user\employee;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDocument;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
class UserDocumentController extends Controller
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
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }
    public function AddDocument(Request $request){
        $user_id = Auth::user()->id;
        $update=array();
        $upd = EmpDocument::where(['user_id'=>$user_id])->get();
        if(count($upd)>0){
            $update = $upd;
        }
        $organisation = $this->GetOrganisation($user_id);
        return view('user.employee.add_document_details',compact('organisation','update'));
    }
    public function UpdateDocument(Request $request){
        $user_id = Auth::user()->id;
        $data=array();
        $count = count($request->doucment_title);
        // dd($count);

        if($request->doucment_title){
            for($l=0; $l<count($request->doucment_title); $l++){
                $empdocument = new EmpDocument();
                $empdocument->user_id = $user_id;
                $empdocument->doucment_title = $request->doucment_title[$l];

                if(!empty($request->doucment_file[$l])){  
                    $fileName4 = strtolower($request->doucment_title[$l]).'_'.$user_id.'_'.preg_replace('/\s\s+/', ' ', $request->doucment_title[$l]).'.'.$request->doucment_file[$l]->extension();
                    $request->doucment_file[$l]->move(public_path('employee/documnet'),$fileName4);
                    $empdocument->doucment_file = $fileName4;
                }
                $empdocument->save();
            }
        }
        return redirect()->back()->with('success', 'Update successfully');
    }    

    public function DeleteDocument($id){
        EmpDocument::where('id',$id)->delete();
        return redirect('add-document')->with('success', 'Deleted successfully');  
    }
}
