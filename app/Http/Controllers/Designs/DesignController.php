<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\DesignRepositoryInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Symfony\Component\String\s;

/**
 * @OA\Info(title="Pixelate", version="0.1")
 */
class DesignController extends Controller
{
    /**
     * @var DesignRepositoryInterface
     */
    protected DesignRepositoryInterface $designRepository ;

    /**
     * DesignController constructor.
     * @param DesignRepositoryInterface $designRepository
     */
    public function __construct(DesignRepositoryInterface $designRepository)
    {
        $this->designRepository = $designRepository;
    }


    /**
     * @OA\Get(
     *     path="/api/designs",
     *     summary="Get designs that are Live",
     *     description="Get all designs available online (set to live )",
     *     tags={"Design"},
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function index()
    {

        $designs = $this->designRepository->withCriteria([
            new LatestFirst(),
            new IsLive(),
           new EagerLoad(['user', 'comments'])
           # new ForUser($this->findDesign(1)->user_id)
        ])->all();


        return DesignResource::collection($designs);
    }



    /**
     * @OA\Get(
     *     path="/api/designs/all",
     *     summary="Get all designs",
     *     description="Get all designs available",
     *     tags={"Design"},
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function allDesigns()
    {

        $designs = $this->designRepository->withCriteria([
            new LatestFirst(),
           new EagerLoad(['user', 'comments'])
        ])->all();


        return DesignResource::collection($designs);
    }


    public function findDesign($id)
    {
        $design =  $this->designRepository->find($id);
        return new DesignResource($design);
    }

    public function update(Request $request, $id)
    {
        $design = $this->designRepository->find($id);

        // based on authorization policy
        $this->authorize('update', $design);

            $this->validate($request, [
                'title' => ['required', 'unique:designs,title,' . $id],
                'description' => ['required', 'string', 'min:20', 'max:140'],
                'tags' => ['required'],
                'team' => ['required_if:assign_to_team, true']
            ]);

       $design =  $this->designRepository->update($id, [
           'team_id'=> $request->team,
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful? false : $request->is_live

     ]);

        // apply the tags
        $this->designRepository->applyTags($id, $request->tags);
        return new DesignResource($design);
    }


    public function destroy($id)
    {
        $design = $this->designRepository->find($id);
        $this->authorize('delete', $design);

        $this->designRepository->delete();

        // delete files associated to this record
        foreach(['thumbnail', 'large','original'] as $size) {
            //check if tge file exists in the database
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            };
        }
        return response()->json(["message" => "Record Deleted"], 200);
    }

    public function like(int $id)
    {
        $this->designRepository->like($id);

        return response()->json(['Like / Unlike' => 'Action successful'], 200);
    }

    public function checkIfUserHasLiked($design_id)
    {

        $isLiked = $this->designRepository->isLikedByUser($design_id);
      //  dd($isLiked);
        return response()->json(["Liked" => $isLiked], 200);
    }

    public function search(Request $request)
    {

        $designs=  $this->designRepository->search($request);
        return DesignResource::collection($designs);
    }

    public function findBySlug($slug)
    {
        $design = $this->designRepository->withCriteria([
            new IsLive()
            ])->findWhereFirst('slug', $slug);
        return new DesignResource($design);
    }

    public function getForTeam($team_id)
    {
        $designs = $this->designRepository
            ->withCriteria([new IsLive()])
            ->findWhere('team_id', $team_id);
        return DesignResource::collection($designs);
    }
    public function getForUser($user_id)
    {
        $designs = $this->designRepository
            ->withCriteria([new IsLive()])
            ->findWhere('user_id', $user_id);
        return DesignResource::collection($designs);
    }


}
