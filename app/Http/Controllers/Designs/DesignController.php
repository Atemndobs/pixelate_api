<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Repositories\Contracts\DesignRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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


    public function index()
    {
        $designs = $this->designRepository->all();
        return DesignResource::collection($designs);
    }


    public function findDesign($id)
    {
        $design =  $this->designRepository->find($id);
        return new DesignResource($design);
    }

    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);
      //$this->authorize('update', $design);

            $this->validate($request, [
                'title' => ['required', 'unique:designs,title,' . $id],
                'description' => ['required', 'string', 'min:20', 'max:140'],
                'tags' => ['required']
            ]);

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful? false : $request->is_live
     ]);

        // apply the tags
        $design->retag($request->tags);
        return new DesignResource($design);
    }


    public function destroy($id)
    {
        $design = Design::findOrFail($id);
        $this->authorize('delete', $design);

        $design->delete();

        // delete files associated to this record
        foreach(['thumbnail', 'large','original'] as $size) {
            //check if tge file exists in the database
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            };
        }
        return response()->json(["message" => "Record Deleted"], 200);
    }
}
