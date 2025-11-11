<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ClientBeneficiaries;
use App\Models\Librarys;
use App\Models\User;
use App\Models\Types_of_voice;
use App\Helpers\Common;
use DB;
use Validator;
use File;

class Types_voiceController extends Controller
{
    /***
    *   Developed by: dhruvish suthar
    *   Description: Display list Voice type
    ***/
    public function index()
    {
        return view('admin.Type_of_voice.index');
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Validate create Voice type
    ***/
    public function validator($request)
    {
        return $this->validate($request, [
            'name' => 'required|string|max:255',
            'mp3_file_name' => 'required',
        //  'type' => 'required',
      ]);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Validate update Voice type
    ***/
    public function updateValidator($request)
    {
        return  $this->validate($request, [
            'name' => 'required|unique:librarys,name,' . $request->id,
       ]);
    }

    public function list()
    {
        $types_of_voice = '';
        $types_of_voice = Types_of_voice::select('id','type_of_voice','status','created_at')->orderBy('id','desc')
          ->get();

          //dd(@$types_of_voice);
        
        return datatables()->of($types_of_voice)
            ->editColumn('status', function ($type_of_voice) {
                $inactive_url = route('admin.type_of_voice.block', $type_of_voice->id);
                $active_url = route('admin.type_of_voice.active', $type_of_voice->id);

                if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.status'))){

                    if($type_of_voice->status=='active')
                    {
                            return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to add in Black List" >Active</a>';
                    }
                    else
                    {
                        return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Black Listed</a>';
                    }
                }
            })
            ->editColumn('created_at', function ($type_of_voice) 
            {
                return date('d/m/Y',strtotime($type_of_voice->created_at));
            })
            ->escapeColumns([])
            ->addColumn('action', function ($type_of_voice) 
            {

                $edit_link = "";
                $delete_link = "";
                $edit_url = route('admin.type_of_voice.edit', $type_of_voice->id);
                $delete_url = route('admin.type_of_voice.destroy', $type_of_voice->id);

                 if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.edit'))){

                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                    <span class="edit-icon"></span>
                  </a>';

                }
                if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.delete'))){

                    $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                    <span class="delete-icon"></span>
                  </a>';

                }
              return $edit_link.''.$delete_link;

            })
          ->make(true);
    }

    public function view($beneficiarId)
    {
        $types_of_voice = Types_of_voice::where('id',$voiceId)->first();
        $data['types_of_voice'] = $types_of_voice;
        return view('admin.type_of_voice.view',$data);
    }

    public function type_of_voiceBlock(Request $reques,$id)
    {
        $type_of_voice = Types_of_voice::findOrFail($id);
        $type_of_voice->status = 'inactive';
        $type_of_voice->save();

       // dd($librarys,'hg');
        /** Log activity **/

        $notification = array(
          'message' =>  __('Voice Type deactivated successfully.'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }


    public function Voice_typeActive(Request $request,$id)
    {
        $librarys = Types_of_voice::findOrFail($id);
        $librarys->status = 'active';
    
        $librarys->save();

       // dd($librarys,'fggg');

        $notification = array(
          'message' =>  __('Voice Type activated successfully.'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display create Voice type
    ***/

    public function createVoice_type()
    {
       $types_of_voice = new Types_of_voice;
        return view('admin.Type_of_voice.create')->with(compact('types_of_voice'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save Voice type in database
    ***/
    public function storeVoice_type(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'type_of_voice' => 'required|string|unique:types_of_voice,type_of_voice|max:255',
        ]);
        
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }

        //store  here
      
        $input['type_of_voice'] = $request->type_of_voice;
        $types_of_voice = Types_of_voice::create($input);

        /** Log activity **/

        $notification = array(
          'message' =>  __('Type created successfully.'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.type_of_voice.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display edit Voice type
    ***/
    public function Voice_typeEdit($id)
    {
        $types_of_voice  = Types_of_voice::findOrFail($id);
        return view('admin.Type_of_voice.create')->with(compact('types_of_voice'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save updated Voice type
    ***/
    public function Voice_typeUpdate(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'type_of_voice' => 'required|string|unique:types_of_voice,type_of_voice,' . $request->id,
        ]);
        
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        
        $types_update = Types_of_voice::findOrFail($id);

        $input['type_of_voice'] = $request->type_of_voice;
       
        $types_update->update($input);

        /** Log activity **/
      
        $notification = array(
            'message' => __('Type updated successfully.'),
            'alert-type' => 'success'
          );

        return redirect()->route('admin.type_of_voice.index')->with($notification);
            
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Delete Voice type
    ***/
    public function Voice_typeDestroy($id)
    {
        $Types = Types_of_voice::findOrFail($id);

        $Types->delete();

        $notification = array(
            'message' =>  __('Voice Type deleted successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.type_of_voice.index')->with($notification);
    }


}