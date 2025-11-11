<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Settings;
use App\Models\Types_of_voice;
use App\Models\CMSTemplate;
use App\Models\Events;
use App\Models\Faqs;
use App\Models\Admin;
use App\Helpers\Common;


class SettingsController extends Controller
{
    
     public $path ='admin.cms_view.';
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: validator for update settings request
    ***/
    public function updateValidator($request)
    {
        return  $this->validate($request, [
         'site_title' => 'required|max:500',
         'site_logo' => 'image|max:5120',
         'footer_logo' => 'image|max:5120',
         'site_favicon' => 'image|max:5120',
         'about_banner' => 'image|max:5120',
         'details_banner' => 'image|max:5120',
       ]);
    }

    
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display settings page
    ***/
    public function updateSettings()
    {
        $settings = Settings::first();
        $types_of_voice = Types_of_voice::where('status','active')->orderBy('id','desc')
          ->get();

        $types_events_defo = Events::where('status','active')->orderBy('id','desc')
          ->get();
        $admins = Admin::where('status','active')->get();
        return view('admin.settings.update')->with(compact('settings','admins','types_of_voice','types_events_defo'));
    }

    public function about_us(){

        $page_title = 'About Us';
        $aboutus = CMSTemplate::where('name','About Us')->first(); 
        return view('cmspagesApi/aboutUs')->with('aboutus',$aboutus);   
    }

    public function terms_view(){

        $page_title = 'Terms Conditions';
        $terms = CMSTemplate::where('name','Terms of Use')->first(); 
        return view('cmspagesApi/terms_conditions')->with('terms',$terms);   
    }

    public function privacy_view(){

        $privacy = CMSTemplate::where('name','Privacy Policy')->first(); 

        return view('cmspagesApi/privacy_policyData')->with('privacy',$privacy);   
    }

    public function refund_policy(){

        $refund = CMSTemplate::where('name','Refund Policy')->first(); 
        return view('cmspagesApi/refund_policy')->with('refund',$refund);   
    }

    public function faq(){
        $faq = Faqs::orderBy('id','desc')->get();
        return view($this->path.'index')->with('faq',$faq);
    }

    public function safety_report(){
        $safety_report = CMSTemplate::where('name','Safety & Report')->first(); 
         return view('cmspagesApi/safety_report')->with('safety_report',$safety_report);  
    }

    public function FaqList()
    {
        $faq_templates = '';
        $faq_templates = Faqs::select('id','title','description')->get();

        return datatables()->of($faq_templates)
          ->editColumn('description', function ($faq_templates) {

            $words = explode(' ', $faq_templates->description);

            // Limit to the first 50 words
            $limitedText = implode(' ', array_slice($words, 0, 10));

            // Check if there are more words
            if (count($words) > 10) {
                $limitedText .= '..........';
            }

            return $limitedText;
          })
         
          ->addColumn('action', function ($faq_templates) {

             if(Common::hasPermission(config('settings.admin_modules.faq'),config('settings.permissions.edit'))){

                $edit_url = route('admin.faq.edit', $faq_templates->id);
                return '<a class="btn action-edit btn-primary btn-action-icon edit-btn-clr" title="Edit" href="'.$edit_url.'">
                <span class="edit-icon"></span>
                </a>';

            }
             
          })
          ->escapeColumns([])
          ->make(true);
    }

    public function faq_add(){
        return view($this->path.'faq_add');
    }

    public function faq_save(Request $request){

        $s = new Settings();
        $s->title = $request->title;
        $s->description = $request->description;
        $s->save();

        $notification = array(
                    'message' => __('FAQ added Successfully.'),
                    'alert-type' => 'success'
                  );

        return redirect()->route('admin.faq')->with($notification);
    }

    public function faq_edit($id){
        $s = Faqs::where('id',$id)->first();
        return view($this->path.'create')->with('s',$s);
    }

    public function faq_update($id,Request $request){
        $s = Faqs::where('id',$id)->first();
        $s->title = $request->title;
        $s->description = $request->description;
        $s->save();

        $notification = array(
                    'message' => __('FAQ updated Successfully.'),
                    'alert-type' => 'success'
                  );

        return redirect()->route('admin.faq')->with($notification);
    } 


    public function faq_view(){
        $faq = Faqs::orderBy('id','desc')->get();
        return view($this->path.'faq_view')->with('faq',$faq);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Validate update settings request, If invalid throw error.
    *   If valid, Save logo and favicon in respective folder if uploaded and save data in database
    ***/
    public function saveSettings(Request $request)
    {
        try
        {
            $settings = Settings::first();

            if ($this->updateValidator($request)) {            
                $input = $request->except(['_token']);

                 /*** If logo is uploaded, save it to logo folder ***/
                if($request->site_logo)
                {
                    $fileName = "logo.".$request->site_logo->getClientOriginalExtension();
                    $path = $request->site_logo->move(config('settings.site_logo_folder'),$fileName);
                    
                    $input['site_logo'] = $fileName;
                }

                 /*** If footer logo is uploaded, save it to logo folder ***/
                 if($request->footer_logo)
                 {
                     $fileName = "footer_logo.".$request->footer_logo->getClientOriginalExtension();
                     $path = $request->footer_logo->move(config('settings.site_logo_folder'),$fileName);
                     
                     $input['footer_logo'] = $fileName;
                 }

                /*** If favicon is uploaded, save it to favicon folder ***/
                if($request->site_favicon)
                {
                    $fileName = "favicon.".$request->site_favicon->getClientOriginalExtension();
                    $request->site_favicon->move(config('settings.site_favicon_folder'),$fileName);
                    $input['site_favicon'] = $fileName;
                }

                 /*** If about banner is uploaded, save it to banner folder ***/
                 if($request->about_banner)
                 {
                     $fileName = "about_banner_".time().".".$request->about_banner->getClientOriginalExtension();
                     $request->about_banner->move(config('settings.banner_folder'),$fileName);
                     $input['about_banner'] = $fileName;
                 }

                  /*** If details banner is uploaded, save it to banner folder ***/
                if($request->details_banner)
                {
                    $fileName = "detail_banner_".time().".".$request->details_banner->getClientOriginalExtension();
                    $request->details_banner->move(config('settings.banner_folder'),$fileName);
                    $input['details_banner'] = $fileName;
                }

               
                $settings->update($input);

                /** Log activity **/
                Common::logActivity($settings , Auth::guard('admin')->user()->id , $settings->toArray() ,"Website settings updated.");

                $notification = array(
                    'message' => __('messages.settingsUpdateSuccess'),
                    'alert-type' => 'success'
                  );

                return redirect()->route('settings.update')->with($notification);
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage(); die;
            $notification = array(
              'message' => __('messages.serverError'),
              'alert-type' => 'error'
            );
            return redirect()->route('settings.update')->with($notification);
        }

        
    }

    
}
