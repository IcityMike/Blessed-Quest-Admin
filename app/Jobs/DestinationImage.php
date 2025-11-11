<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Destination_details_img;
use Intervention\Image\Facades\Image; // Make sure to install Intervention Image if you use it
use App\Helpers\Common;
class DestinationImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    protected $uid;

    protected $store_destinationId;

    public function __construct($destinationImg, $userid, $destination_data)
    {
        $this->path = $destinationImg;

        $this->uid = $userid;

        $this->store_destinationId = $destination_data->id;

        //dd($this->store_location_detailsId);
    }

    public function handle()
    {
        //dd($this->store_location_detailsId,$this->uid);
        foreach ($this->path as $destinationImg) {

           if($destinationImg){

              //$imagePathsourceImg = Common::imgURL_get($sourceImg,"Source_details_img");

                $imagePathsourceImg = Common::imgURL_get(@$destinationImg,"Destination_details_img");

            }else{
               $imagePathsourceImg = '';
            }
            /// download img ///////////

            $store_imgs = Destination_details_img::create([
                'user_id' => @$this->uid,
                'destination_details_id' => @$this->store_destinationId,
                'image' => @$destinationImg,
            ]);
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
