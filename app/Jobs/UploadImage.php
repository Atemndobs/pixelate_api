<?php

namespace App\Jobs;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Design
     */
    protected $design;


    /**
     * Create a new job instance.
     *
     * @param Design $design
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $filename = $this->design->image;
        $disk = $this->design->disk;
        // replaced in function by temp_file
        // $original_file = storage_path().'/uploads/original/'.$filename;



        try {
            // create large image and save to temp
            $this->createImage( 'large', 800, 600);
            // create thumbnail image
            $this->createImage( 'thumbnail',250, 200);

            // store images to permanent location
            //Original image
            $this->storeToPermanentLocation('original', $disk, $filename);

            //Large image
            $this->storeToPermanentLocation('large', $disk, $filename);

            //Thumbnail image
            $this->storeToPermanentLocation('thumbnail', $disk, $filename);

            // update the database record with success flag
            $this->design->update([
                'upload_successful' => true
            ]);

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        };
    }

    protected function createImage(string $size, int $with, int $height = null)
    {

        $filename = $this->design->image;
        $original_file = storage_path().'/uploads/original/'.$filename;
        $temp_file = storage_path('uploads/'.$size.'/'.$filename);


       return Image::make($original_file)
            ->fit($with, $height, function ($constraint){
                $constraint->aspectRatio();
            })
            ->save($temp_file);
    }

    protected function storeToPermanentLocation( string $size, string $disk, string $filename)
    {
        $temp_file = storage_path('uploads/'.$size.'/'.$filename);

        $path = 'uploads/designs/'.$size.'/'.$filename;
        $source = fopen($temp_file, 'r+');
        $storage = Storage::disk($disk);

        // Storage::disk($disk)->put($path, fopen($original_file, 'r+'))
        if ($storage->put($path, $source)){
            File::delete($temp_file);
        }
    }
}
