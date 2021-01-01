<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\Contracts\DesignRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * @var CommentRepositoryInterface
     */
    protected CommentRepositoryInterface $commentRepository;
    /**
     * @var DesignRepositoryInterface
     */
    private DesignRepositoryInterface $designRepository;

    /**
     * CommentController constructor.
     * @param CommentRepositoryInterface $commentRepository
     * @param DesignRepositoryInterface $designRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository, DesignRepositoryInterface $designRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->designRepository = $designRepository;
    }



    public function index()
    {
        $comments = $this->commentRepository->all();
        return CommentResource::collection($comments);
    }



    public function store(Request $request, $design_id)
    {
        $this->validate($request, [
           'body'=> 'required|string'
        ]);

        $comment = $this->designRepository->addComment($design_id, [
            "body" => $request->body,
            "user_id" => $this->designRepository->find($design_id)->user->id
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return Response
     */
    public function show(Comment $comment)
    {
        //
    }


    /**
     * @param Request $request
     * @param int $id
     * @return CommentResource
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $comment = $this->commentRepository->find($id);
        $this->authorize('update', $comment);

        $this->validate($request, [
            'body'=> 'required'
        ]);

        $comment = $this->commentRepository->update($id, [
            'body' => $request->body
        ]);

        return new CommentResource($comment);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @throws AuthorizationException
     */
    public function destroy(int $id)
    {
        $comment = $this->commentRepository->find($id);
        $this->authorize('delete', $comment);

        $this->commentRepository->delete($id);

        return \response()->json(['message' => 'Comment Deleted'], 200);
    }
}
