<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Jobs\UploadImage;
use App\Models\Design;
use App\Models\Post;
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
use Symfony\Component\Yaml\Yaml;

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
            'image' => ['required', 'mimes:jpeg,jpg,gif,bmp,png']
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
        $user = User::findOrFail($id);
        $images = DB::table('posts')->select('imageUrl')
            ->where(['user_id' => $id])
            ->get();

        if ($images->count() === 0) {
            return response(
                ["message" => "No images found for ".$user->name ],
                404
            );
        }


       // echo asset('storage/eVn9NU9RrT1u8d1fS4UguGpqL4t10MsVnYLKvcoR.png');
        return response(
            ["images for ".$user->name => $images],
            200
        );
    }


    /**
     * @param $post_id
     * @return false|string
     *
     *     path="/api/image/{id}",
     *     summary="Get Image",
     *     description="Get image after upload",
     *     tags={"Image"},
     *
     * @OA\Delete (
     * path="/api/image/{user_id}/{post_id}",
     * summary="Delete Image",
     * description="Delete A Post by Id",
     * tags={"Image"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="true"),
     *       @OA\Property(property="message", type="string", example="Post deleted successfully"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     )
     * )
     */
    public function deleteImage($user_id, $post_id)
    {

       // $posts = User::findOrFail($user_id)->posts()->get();


       $post =  Post::findOrFail($post_id);

           if ((int)$user_id === $post->user_id)
           {
               $post->update(['imageUrl' => '']);
           }

        return response(
            ['message' => $post],
            200
        );

    }
}
