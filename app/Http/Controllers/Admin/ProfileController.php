<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Helpers\Common;

use File;

class ProfileController extends Controller
{
   
  
   /***
    *   Developed by: Dhruvish suthar
    *   Description: Validate edit profile request
    ***/
    public function validator($request)
    {
        return $this->validate($request, [
         'first_name' => 'required|string|max:255',
         'last_name' => 'required|string|max:255',
         'profile_picture' => 'image|max:5120',
      ]);
    }

   
    /***
    *   Developed by: Dhruvish suthar
    *   Description: Display edit profile page
    ***/
    public function editProfile()
    {
        $admin = Admin::findOrFail( Auth::guard('admin')->user()->id);
        return view('admin.profile.edit')->with(compact('admin'));
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: Validate change password request
    ***/
    public function passwordValidator($request, $admin)
    {
        return $this->validate($request, [
        'current_password' => ['required','min:6', function ($attribute, $value, $fail) use ($admin) {
            if (!Hash::check($value, $admin->password)) {
                return $fail( __('messages.invalidCurrentPassword'));
            }
        }],
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6',
      ]);
    }

     /***
    *   Developed by: Dhruvish suthar
    *   Description: Display change password page
    ***/
    public function changePassword()
    {
        return view('admin.profile.changePassword');
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Save updated passowrd in database
    ***/
    public function updatePassword(Request $request)
    {
        $admin = Admin::findOrFail(Auth::guard('admin')->user()->id);
        if ($this->passwordValidator($request, $admin)) {
           
            
            $input['password'] = bcrypt($request->password);
            $admin->update($input);

            /*** Send change password notification to user by email ***/
            $admin->sendPasswordResetSuccessNotification($admin);

        
            $notification = array(
                'message' => __('messages.passwordUpdateSuccess'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.loginindex')->with($notification);
        }
    }
    /***
    *   Developed by: Dhruvish suthar
    *   Description: Save updated profile details in database
    ***/
    public function updateProfile(Request $request)
    {
        if ($this->validator($request)) {
            $input = $request->except(['_token','email']);
            $admin = Admin::findOrFail(Auth::guard('admin')->user()->id);
           
            /*** If profile picture is uploaded, save it to admin_pictures folder ***/
            if($request->profile_picture_hidden)
            {  
               if($admin->profile_picture)
               {
                   $file = config('settings.admin_picture_folder')."/".$admin->profile_picture;
                    if(File::exists($file)) {
                        File::delete($file);
                    }
               }
               
                $input['profile_picture'] = $request->profile_picture_hidden;
            }


            $admin->update($input);

         
            $notification = array(
                'message' => __('messages.profileUpdateSuccess'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.editProfile')->with($notification);
        }
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Send email on new email address on update email request
    ***/
    public function updateEmail($email)
    {
        $found = Admin::where('email','=',$email)->first();
        try
        {
            if(!$found)
            {
                
                $data['email'] = $email;
                $data['user'] = 'admin';
                $data['userId'] = Auth::guard('admin')->user()->id;

                /*** Send verification email to admin ***/
                \Mail::to($email)->send(new \App\Mail\VerifyNewEmail($data));             

                /*** Send email update notification to admin ***/
                $admin = Admin::find(Auth::guard('admin')->user()->id);
                $admin->sendUpdateEmailNotification($admin);                 

                return redirect()->route('admin.editProfile')->with('success',__('messages.emailUpdateMailSentSuccess'));
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage(); die;
            return redirect(route('admin.editProfile'))->withErrors([__('messages.serverError')]);
        }
       
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Check for dupicate email of admin while update email
    ***/
    public function checkDuplicateEmail(Request $request)
    {
        
        $email = $request->get('email');
        $client = Admin::where('email','=',$email)->first();
        if($client)
            echo "false";
        else
            echo "true";
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Crop and upload Profile picture and return uploaded image name
    ***/
   public function cropProfilePicture(Request $request)
   {

        $dir = 'uploads/admin_pictures/';
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true); // Ensure the directory is created
        }
        
        $data = $request->image;
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $fileName = "picture_".time().".". 'png';
       
       // $imageName = config('settings.admin_picture_folder')."/".$fileName;

        $path = public_path('uploads/admin_pictures/' . $fileName);

        //dd($path,$data);
        file_put_contents($path, $data);

        //$path = $request->profile_picture->move(config('settings.admin_picture_folder'),$fileName);
       
        echo $fileName;
   }

     /***
    *   Developed by: Dhruvish suthar
    *   Description: Verify new email and update in specific table.
    ***/
    public function verifyUpdatedEmail($id, $email, $user) {

        try
        {
            $userData = null;
            if($user == 'admin')
            {
                $userData = Admin::find($id);
               
            }
           
            if($userData)
            {
                $userData->email = $email;
                $userData->save();
            }

            
            return redirect()->route($user.'.editProfile')->with('success',__('messages.emailUpdateSuccess'));
        }
        catch (\Exception $e) {
         
             return redirect()->route($user.'.editProfile')->withErrors(['error', __('messages.serverError')]);
        }
       
    }

   
}
