<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use DB;

class ImageController extends Controller
{
   
    

   /***
    *   Developed by: Radhika Savaliya
    *   Description: Upload image
    ***/

   public function uploadImage(Request $request)
   {
       
            // Render HTML output 
            if($request->hasFile('upload')) {
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                if(substr($request->file('upload')->getMimeType(), 0, 5) == 'image')
                {
                    /*** Get image size in KB ***/
                    $filesize = number_format($request->file('upload')->getSize() / 1024, 2);
                    if( $filesize <= 5120)
                    {
                        $originName = $request->file('upload')->getClientOriginalName();
                        $fileName = pathinfo($originName, PATHINFO_FILENAME);
                        $extension = $request->file('upload')->getClientOriginalExtension();
                        $fileName = $fileName.'_'.time().'.'.$extension;
                    
                        $request->file('upload')->move($request->input('amp;folder'), $fileName);
            
                        
                        $url = asset($request->input('amp;folder').'/'.$fileName); 
                        $msg = ''; 
                        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                        
                        @header('Content-type: text/html; charset=utf-8'); 
                        echo $response;
                    }
                    else
                    {
                        
                        $msg = "Please upload image with max 5 MB size";
                        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '$msg')</script>";
                        echo $response;
                    }
                   
                }
                else
                {
                    
                    $msg = "Please upload only image file";
                    $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '$msg')</script>";
                    echo $response;
                }
                
            }
      
       
   }
   
}
