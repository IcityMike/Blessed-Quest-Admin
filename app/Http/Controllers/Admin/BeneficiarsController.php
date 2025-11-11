<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ClientBeneficiaries;
use App\Models\User;
use App\Helpers\Common;
use DB;

class BeneficiarsController extends Controller
{
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display list client users page
    ***/
    public function index()
    {
        return view('admin.beneficiars.index');
    }

    public function list()
    {
        $beneficiars = '';
        $beneficiars = ClientBeneficiaries::select('id','user_id','name','email','contact_number','status','created_at')->orderBy('id','desc')
          ->get();
        
        return datatables()->of($beneficiars)
            ->editColumn('user_name', function ($beneficiars) 
            {
                $user = User::select('name')->where('id',$beneficiars->user_id)->first();
                return ($user) ? ucwords($user->name) : '';
            })
            ->editColumn('status', function ($beneficiars) {
                $inactive_url = route('admin.beneficiars.block', $beneficiars->id);
                $active_url = route('admin.beneficiars.active', $beneficiars->id);
                if($beneficiars->status==1)
                {
                        return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to add in Black List" >Active</a>';
                }
                else
                {
                    return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Black Listed</a>';
                }
            })
            ->editColumn('created_at', function ($beneficiars) 
            {
                return date('d/m/Y',strtotime($beneficiars->created_at));
            })
            ->escapeColumns([])
            ->addColumn('action', function ($beneficiars) 
            {
                $view_url = route('admin.beneficiars.view', $beneficiars->id);
                return '<a class="action-view btn btn-primary btn-action-icon action-edit edit-btn-clr" href="'.$view_url.'"><span class="eye-icon"></span></a>';
            })
          ->make(true);
    }

    public function view($beneficiarId)
    {
        $beneficiar = ClientBeneficiaries::where('id',$beneficiarId)->first();
        $user = user::select('name')->where('id',$beneficiar->user_id)->first();
        $data['beneficiar'] = $beneficiar;
        $data['user'] = $user;
        return view('admin.beneficiars.view',$data);
    }

    public function beneficiarBlock(Request $reques,$id)
    {
        $client = ClientBeneficiaries::findOrFail($id);
        $client->status = 0;
        $client->save();

        /** Log activity **/
        Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Beneficiar add in black list.");

        $notification = array(
          'message' =>  __('messages.beneficiaryDeactivateSuccess'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }

    public function beneficiarActive(Request $request,$id)
    {
        $client = ClientBeneficiaries::findOrFail($id);
        $client->status = 1;

        /** Log activity **/
        Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Beneficiar status updated to active.");

        $client->save();

        $notification = array(
          'message' =>  __('messages.beneficiaryActivateSuccess'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }
}