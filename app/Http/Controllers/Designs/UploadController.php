<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Jobs\UploadImage;
use App\Models\Design;
use App\Models\User;
use App\Repositories\Contracts\DesignRepositoryInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForDesign;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/designs",
     * summary="Upload Image",
     * description="Uploads Image Local or to S3 bucket",
     * tags={"Design"},
     * security={ {"token": {} }},
     *
     *     @OA\RequestBody(
     *          required=true,
     *         description="Upload images request body",
     *         @OA\MediaType(
     *             mediaType="application/octet-stream",

     *             @OA\Schema(
     *
     *                 type="string",
     *                 format="binary",
     *
     *             )
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Design")
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="The given data was invalid."),
     *      @OA\Property(property="errors", type="object",
     *          @OA\Property(property="image",
     *              example={"The image field is required."}
     *          ),
     *      ),
     *      ),
     *      ),
     * )
     */
    public function upload(Request $request)
    {

        $this->validate($request, [
            'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:2048']
        ]);

/*        if ($request->has('image')){
           $request->image->store('public');
        }*/

        //get the image from request

        $image = $request->file('image');
       # $image_path = $image->getPathname();
        $image->getPathname();

        // get the original file name and replace any space with _
        //Business Cards.png = timestamp()_business_card.png

        $filename = time()."_".preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // move the image to the temp location

        #$tmp = $image->storeAs('uploads/original', $filename, 'tmp');
        $image->storeAs('uploads/original', $filename, 'tmp');




    //\Storage::disk('public')->put( $filename, '');


        // insert / create database record for the design
/*        $design = auth()->user()->designs()->create([
            'image' => $filename,
            'disk' => config('site.upload_disk')
        ]);*/
        $user = User::firstOrFail();
        $design = $user->designs()->create([
            'image' => $filename,
            'disk' => config('site.upload_disk')
        ]);

        $design_id = $design->id;

        // dispatch a job handle the image manipulation


       $this->dispatch(new UploadImage($design));

        return response()->json($design,200);
    }


    /**
     * @OA\Get(
     *     path="/api/image/{id}",
     *     summary="Get Image",
     *     description="Get image after upload",
     *     tags={"Image"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="image", type="string")
     *             )
     *          ),
     *      @OA\Response(
     *         response=404,
     *         description="UDesign Not found",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Not Found"),
     *         )
     *      )
     * )
     */
    public function getImage($id)
    {
        $user = User::first();
        $images = DB::table('post')->select('imageUrl')
            ->where('id',  $id)
            ->where('user_id', $user->id);


      return $images;

        //return Storage::disk()->allDirectories();

        echo asset('storage/eVn9NU9RrT1u8d1fS4UguGpqL4t10MsVnYLKvcoR.png');


        // return response()->file(Storage::get('my_image.jpg'), ['Content-Type' => 'image/jpeg']);
       // return response()->file(\Storage::get($image->image), ['Content-Type' => 'image/jpeg']);
/*        return response(
            ["image" => $image_link],
            200
        );*/
    }
}
