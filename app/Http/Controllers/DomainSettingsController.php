<?php

namespace App\Http\Controllers;

use App\Events\FileExportedEvent;
use App\Models\Post;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\Song\SpotifyService;
use Illuminate\Http\Request;

class DomainSettingsController extends Controller
{

    public Request $request;

    public SettingsService $settings;

    /**
     * DomainSettingsController constructor.
     * @param Request $request
     * @param SettingsService $settings
     */
    public function __construct(Request $request, SettingsService $settings)
    {
        $this->request = $request;
        $this->settings = $settings;
    }


    /**
     * Delete all data from Model.
     * POST /posts
     *
     * @OA\Post(
     * path="/api/settings/clear/{model}",
     * summary="Clean Up Model",
     * description="clear data in model",
     * tags={"Settings"},
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string", example="posts"
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Model Cleared")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     )
     *)
     */
    public function index()
    {
        if ($this->request->model === 'null') {
            return response([
                'error' => 'No Model Selected'
            ], 404);
        }

        $tables = \DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

       // $models = $this->settings->getModels();
        $folder = $this->request->model;

        if (!in_array($this->request->model, $tables)) {
            return response([
                'error' => "{$folder} Does not Exist in this Domain"
            ], 404);
        }

        try {
            \Artisan::call("clear:assets $folder");
            \Artisan::call("reset:table $folder");
            return response([
               'message' => "{$folder} cleared"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    public function clearImages()
    {
    }

    /**
     * Clean Up models
     * Get /settings
     *
     * @OA\Get(
     * path="/api/settings/models",
     * summary="Clean Up Model",
     * description="clear data in model",
     * tags={"Settings"},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Array of models")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     )
     *)
     */
    public function getModels()
    {

        try {
            $models = $this->settings->getModels();
            return response([
                'message' => $models,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], 419);
        }
    }


    /**
     * Populate given model.
     * POST /settings
     *
     * @OA\Post(
     * path="/api/settings/model/populate/{model}",
     * summary="Populate Model",
     * description="Add  data for model",
     * tags={"Settings"},
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string", example="posts"
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Model Cleared")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     )
     *)
     */
    public function populateData()
    {
        $model = $this->request->model;

        try {
            \Artisan::call("clear:assets $model");
            \Artisan::call("reset:table $model");
            \Artisan::call("reset:table users");
            $_model  =  ucwords($model);
/*            if (User::where(['email' => 'bamarktfact@gmail.com'])->count() > 0) {
                \Artisan::call("reset:table users");
            }*/
            \Artisan::call("db:seed --class=AtemTableSeeder");
            \Artisan::call("db:seed --class={$_model}TableSeeder");
            return response([
                'message' => "{$model} Populated'"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], 422);
        }

        // php artisan db:seed --class=PostsTableSeeder
    }


    /**
     * Create Reaction Types
     * POST /posts
     *
     * @OA\Post(
     * path="/api/settings/types",
     * summary="Create / Reset Types",
     * description="Create / Reset Reaction Types",
     * tags={"Settings"},
     * @OA\RequestBody(
     *    required=true,
     *    description="The Types to be Created",
     *    @OA\JsonContent(
     *       @OA\Property(property="types", type="string", example="Like,Laugh,Happy,Surprise,Smile"),
     *       @OA\Property(property="reset", type="boolean", example=false),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Model Cleared")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Error"),
     *     )
     *     )
     * )
     */
    public function createLikeTypes()
    {
       // $_model  =  ucwords($model);

        $reset = $this->request->reset;
        $types = $this->request->types;

        try {
            $created = $this->settings->resetLikeTypes($reset, $types);
            return response([
                'message' => [
                    'reset' => $reset,
                    'types' => $created
                ]
            ], 201);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], 422);
        }
    }


    /**
     * Get all ReactionTypes
     * POST /posts
     *
     * @OA\Get(
     * path="/api/settings/types",
     * summary="Get All ExistingTpes",
     * description="Get all types",
     * tags={"Settings"},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Like")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     )
     *)
     */
    public function getTypes()
    {
        return response([
            'message' => [
                'types' => $this->settings->getTypes()
            ]
        ], 200);
    }

    /**
     * Reset Database
     * POST /posts
     *
     * @OA\Post (
     * path="/api/settings/reset/db",
     * summary="Reset Databease",
     * description="Reses DB",
     * tags={"Settings"},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Database Reset")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     )
     *)
     */
    public function resetDB()
    {

        try {
            $reset = $this->settings->resetDb();
            return response([
                'message' => [
                    'reset' => $reset,
                ]
            ], 201);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], 422);
        }
    }


    /**
     * Create Csv | pdf and Export
     * POST /settings
     *
     * @OA\Post  (
     * path="/api/settings/export",
     * summary="Export CSV or Excel or Pdf",
     * description="Export ",
     * tags={"Settings"},
     * @OA\RequestBody(
     *    required=true,
     *    description="The Types to be Exported",
     *    @OA\JsonContent(
     *       @OA\Property(property="type", type="string", example="csv"),
     *       @OA\Property(property="model", type="string", example="post"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Exported")
     *    ),
     * ),
     * @OA\Response(
     *    response=419,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unprocessable Entity")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Model not found"),
     *     )
     *     )
     *)
     */
    public function export()
    {
        $type = $this->request->type;
        $model = $this->request->model;

        event(new FileExportedEvent($type));
        try {
            return $this->settings->export($type, $model);
/*            return response([
                'message' => [
                    'reset' => $export,
                ]
            ], 201);*/
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], 422);
        }
    }
}
