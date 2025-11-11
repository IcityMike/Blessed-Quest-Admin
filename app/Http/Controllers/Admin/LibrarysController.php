<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ClientBeneficiaries;
use App\Models\Librarys;
use App\Models\Librarys_audio;
use App\Models\User;
use App\Models\Events;
use App\Models\Types_of_voice;
use App\Helpers\Common;
use DB;
use Validator;
use File;

class LibrarysController extends Controller
{
    /***
    *   Developed by: dhruvish suthar
    *   Description: Display list client users page
    ***/
    public function index()
    {

        return view('admin.Librarys.index');
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Validate create library request
    ***/
    public function validator($request)
    {
        return $this->validate($request, [
            'name' => 'required|string|max:255',
          // 'mp3_file_name' => 'required',
        //  'type' => 'required',
      ]);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Validate update library request
    ***/
    public function updateValidator($request)
    {
        return  $this->validate($request, [
           // 'name' => 'required|string|max:255',
            'name' => 'required|unique:librarys,name,' . $request->id,
           // 'mp3_file_name' => 'required',
       ]);
    }

    public function updateValidatorsecond($request)
    {
        return  $this->validate($request, [
           // 'name' => 'required|string|max:255',
            'name' => 'required|unique:librarys,name,' . $request->id,
           // 'mp3_file_name' => 'required',
       ]);
    }

    public function list()
    {
        $librarys = '';
        $librarys = Librarys::select('id','name','mp3_file_name','status','created_at')->orderBy('id','desc')
          ->get();
        
        return datatables()->of($librarys)
            ->editColumn('user_name', function ($librarys) 
            {
                $user = User::select('name')->where('id',$librarys->user_id)->first();
                return ($user) ? ucwords($user->name) : '';
            })
            ->editColumn('status', function ($librarys) {
                $inactive_url = route('admin.library.block', $librarys->id);
                $active_url = route('admin.library.active', $librarys->id);

                if(Common::hasPermission(config('settings.admin_modules.library'),config('settings.permissions.status'))){

                    if($librarys->status=='active')
                    {
                            return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to add in Black List" >Active</a>';
                    }
                    else
                    {
                        return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Black Listed</a>';
                    }
                }
            })
            ->editColumn('created_at', function ($librarys) 
            {
                return date('d/m/Y',strtotime($librarys->created_at));
            })
            ->escapeColumns([])
            ->addColumn('action', function ($librarys) 
            {

                $edit_link = "";
                $delete_link = "";
                $edit_url = route('admin.library.edit', $librarys->id);
                $delete_url = route('admin.library.destroy', $librarys->id);

                // return '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                //     <span class="edit-icon"></span>
                //   </a>  <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                //     <span class="delete-icon"></span>
                //   </a>';

                 if(Common::hasPermission(config('settings.admin_modules.library'),config('settings.permissions.edit'))){
                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                    <span class="edit-icon"></span>
                  </a>';
                }
                if(Common::hasPermission(config('settings.admin_modules.library'),config('settings.permissions.delete'))){
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
        $beneficiar = ClientBeneficiaries::where('id',$beneficiarId)->first();
        $user = user::select('name')->where('id',$beneficiar->user_id)->first();
        $data['beneficiar'] = $beneficiar;
        $data['user'] = $user;
        return view('admin.beneficiars.view',$data);
    }

    public function libraryBlock(Request $reques,$id)
    {

      //  dd($reques->all(),$id);
        $librarys = Librarys::findOrFail($id);
        $librarys->status = 'inactive';
        $librarys->save();

       // dd($librarys,'hg');
        /** Log activity **/

        $notification = array(
          'message' =>  __('Library deactivated successfully.'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }


    public function libraryActive(Request $request,$id)
    {

          // dd($reques->all(),$id);
        $librarys = Librarys::findOrFail($id);
        $librarys->status = 'active';
    
        $librarys->save();

       // dd($librarys,'fggg');

        $notification = array(
          'message' =>  __('Library activated successfully.'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display create library page
    ***/

    public function createlibrary()
    {
       $librarys = new Librarys;
       $voice_type = Types_of_voice::where('status','active')->get();
       $events = Events::where('status','active')->orderBy('id','desc')->get();
        return view('admin.Librarys.create')->with(compact('librarys','voice_type','events'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save library in database
    ***/
    public function storelibrary(Request $request)
    {
        $validation = Validator::make($request->all(), [
          //  'name' => 'required|name|max:255|unique:librarys,deleted_at,NULL',

            'name' => 'required|string|unique:librarys,name|max:255',
          //  'mp3_file_name' => 'required',
        ]);
        
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }

        //dd($request->all());
        //store  here
      
        $input['name'] = $request->name;
        $input['events_id'] = $request->events_id;
        $input['admin_id'] = Auth::guard('admin')->user()->id;
        /*** If profile picture is uploaded, save it to admin_pictures folder ***/


         $librarys_data_store = Librarys::create($input);
        ///get Librarys_audio

        $voice_type = Types_of_voice::where('status','active')->get();

        foreach ($voice_type as $key => $voice_typeData) {

           // dd($request->file('mp3_file_name')[$key]);
           
            if (@$request->file('mp3_file_name')[$key]) {
                $music_file = $request->file('mp3_file_name')[$key];
                $filename = Auth::guard('admin')->user()->id.uniqid().$music_file->getClientOriginalName();
                $location = public_path('uploads/mp3/');
                $music_file->move($location,$filename);

                $input_audio['mp3_file_name'] = $filename;

                $input_audio['admin_id'] = Auth::guard('admin')->user()->id;
                $input_audio['voice_type'] = $voice_typeData->id;
                $input_audio['library_id'] = $librarys_data_store->id;

                $librarys_audio_save = Librarys_audio::create($input_audio);
            }
        }

        // if ($request->hasFile('mp3_file_name')) {
        //     $music_file = $request->file('mp3_file_name');
        //     $filename = Auth::guard('admin')->user()->id.uniqid().$music_file->getClientOriginalName();
        //     $location = public_path('uploads/mp3/');
        //     $music_file->move($location,$filename);

        //     $input['mp3_file_name'] = $filename;
        // }

       

        /** Log activity **/

       // dd('fsdfsd');

        $notification = array(
          'message' =>  __('Library created successfully.'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.library.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display edit team member page
    ***/
    public function libraryEdit($id)
    {
        $librarys  = Librarys::findOrFail($id);
        $events = Events::where('status','active')->orderBy('id','desc')->get();
        $voice_type = Types_of_voice::where('status','active')->get();
        return view('admin.Librarys.create')->with(compact('librarys','voice_type','events'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save updated team member details in database
    ***/
    public function libraryUpdate(Request $request, $id)
    {
        $librarys_update = Librarys::findOrFail($id);
        $input['name'] = $request->name;
        $input['events_id'] = $request->events_id;
        $librarys_update->update($input);  //Events

        $voice_type = Types_of_voice::where('status','active')->get();

        foreach ($voice_type as $key => $voice_typevalue) {
            
            if(@$request->mp3_file_name[$key]){
              
                // if ($request->file('mp3_file_name')[$key]) {
                //     $music_file = $request->file('mp3_file_name')[$key];
                //     $filename = Auth::guard('admin')->user()->id.uniqid().$music_file->getClientOriginalName();
                //     $location = public_path('uploads/mp3/');
                //     $music_file->move($location,$filename);

                //     $inputfile['mp3_file_name'] = $filename;

                //     $librarys_audio_save->update($inputfile);
                // }


                if ($request->file('mp3_file_name')[$key]) {

                    $librarys_audio_save = Librarys_audio::where('library_id',$id)->where('voice_type',$voice_typevalue->id)->first();

                    if($librarys_audio_save){
                        $librarys_audio_save->delete();
                    }

                    $music_file = $request->file('mp3_file_name')[$key];
                    $filename = Auth::guard('admin')->user()->id.uniqid().$music_file->getClientOriginalName();
                    $location = public_path('uploads/mp3/');
                    $music_file->move($location,$filename);

                    $input_audio['mp3_file_name'] = $filename;

                    $input_audio['admin_id'] = Auth::guard('admin')->user()->id;
                    $input_audio['voice_type'] = $voice_typevalue->id;
                    $input_audio['library_id'] = $id;

                    $librarys_audio_save = Librarys_audio::create($input_audio);
                }
            }
        }

       // dd($librarys_update);

        $notification = array(
            'message' => __('Library updated successfully.'),
            'alert-type' => 'success'
          );

        return redirect()->route('admin.library.index')->with($notification);

        // if($request->mp3_filePath == ''){
        //     if ($this->updateValidator($request)) {
        //         $input = $request->except(['_token','profile_picture','email']);
        //         $librarys_update = Librarys::findOrFail($id);
        //         $input['name'] = $request->name;
        //         $librarys_update->update($input);
        //         /** Log activity **/
        //         $notification = array(
        //             'message' => __('Library updated successfully.'),
        //             'alert-type' => 'success'
        //           );
        //         return redirect()->route('admin.library.index')->with($notification);
        //     }
        // }else{
        //     if ($this->updateValidatorsecond($request)) {
        //         $input = $request->except(['_token','profile_picture','email']);
        //         $librarys_update = Librarys::findOrFail($id);
        //         $input['name'] = $request->name;
        //         if ($request->hasFile('mp3_file_name')) {
        //             $music_file = $request->file('mp3_file_name');
        //             $filename = Auth::guard('admin')->user()->id.uniqid().$music_file->getClientOriginalName();
        //             $location = public_path('uploads/mp3/');
        //             $music_file->move($location,$filename);

        //             $input['mp3_file_name'] = $filename;
        //         }
        //         $librarys_update->update($input);
        //         /** Log activity **/
              
        //         $notification = array(
        //             'message' => __('Library updated successfully.'),
        //             'alert-type' => 'success'
        //           );

        //         return redirect()->route('admin.library.index')->with($notification);
        //     }
        // }    
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Delete library
    ***/
    public function libraryDestroy($id)
    {
        $librarys = Librarys::findOrFail($id);

        /** Remove current team member image from folder */
        if($librarys->mp3_file_name)
        {
            $filePath = 'public/uploads/mp3'."/".$librarys->mp3_file_name;
             $location = public_path('uploads/mp3'."/".$librarys->mp3_file_name);
            // dd(File::exists($location),$location);
            if(File::exists($location)) {
                File::delete($location);
            }
        }

        /** Log activity **/

        $librarys->delete();

        $notification = array(
            'message' =>  __('Library deleted successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.library.index')->with($notification);
    }


}