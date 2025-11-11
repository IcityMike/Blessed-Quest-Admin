<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Source_details_img;
use App\Models\Blessed_location_list;
use App\Models\Blessed_location_imgs;
use Intervention\Image\Facades\Image; // Make sure to install Intervention Image if you use it
use App\Helpers\Common;
class ProcessImagemulty implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    protected $userId;

    protected $store_location_detailsId;

    public function __construct($location_deta,$userId,$store_location_detailsId)
    {
        $this->path = $location_deta;

        $this->userId = @$userId;

        $this->store_location_detailsId = @$store_location_detailsId;
    }

    public function handle()
    {
        //dd($source_data_store);
            foreach($this->path as $locationimgs){
                $store_list_lo = Blessed_location_list::create([
                    'user_id' => @$this->userId,
                    'blessed_location_details_id' => @$this->store_location_detailsId,
                    'description' => @$locationimgs->description,
                    'latitude' =>  @$locationimgs->latitude,
                    'longitude' =>  @$locationimgs->longitude,
                    'mile' =>  @$locationimgs->mile,
                    'name' =>  @$locationimgs->name,
                ]);

                foreach ($locationimgs->images as $valueImg) {

                    /// download img ///////////
                   if($valueImg){

                      $imagePath = Common::imgURL_get($valueImg,"Blessed_location_imgs");

                    }else{
                       $imagePath = '';
                    }
                    /// download img ///////////

                    $store_imgs = Blessed_location_imgs::create([
                    'user_id' => @$this->userId,
                    'image_name' => @$imagePath,
                    'blessed_location_id' => @$this->store_location_detailsId,
                    'blessed_location_list_id' => @$store_list_lo->id,
                    ]);
                }
            }
                        
      
        // dd($imagePathsourceImg);
        // // Get the image from the storage
        // $imagePath = storage_path('app/public/' . $this->path);

        // // Load the image using Intervention Image (optional for resizing)
        // $image = Image::make($imagePath);

        // // Resize the image to a desired dimension (example)
        // $image->resize(800, 600);

        // // Store the resized image back to storage or cloud
        // $newPath = 'processed/' . basename($this->path);
        // Storage::disk('public')->put($newPath, (string) $image->encode());

        // // Optionally, delete the original image after processing
        // Storage::disk('public')->delete($this->path);
    }
}
