<?php

namespace App\Http\Controllers;


use App\Services\SettingsService;
use Illuminate\Http\Request;

class DomainSettingsController extends Controller
{

    public Request $request;

    public SettingsService $models;

    /**
     * DomainSettingsController constructor.
     * @param Request $request
     * @param SettingsService $models
     */
    public function __construct(Request $request, SettingsService $models)
    {
        $this->request = $request;
        $this->models = $models;
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
        if ($this->request->model === 'null'){
            return response([
                'error' => 'No Model Selected'
            ], 404);
        }

        $models = $this->models->getModels();
        $folder = $this->request->model;

        if (!in_array($this->request->model , $models)){
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
        }catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    public function clearImages()
    {

    }

    /**
     * Get all Modals in this App by Class name
     * POST /posts
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
            $models = $this->models->getModels();
            return response([
                'message' => $models,
            ], 200);
        }catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], 419);
        }

    }


    /**
     * Delete all data from Model.
     * POST /posts
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


   //     die(json_encode("db:seed --class={$model}TableSeeder"));

        try {
            \Artisan::call("clear:assets $model");
            \Artisan::call("reset:table $model");
            $_model  =  ucwords($model);
            \Artisan::call("db:seed --class=AtemTableSeeder");
            \Artisan::call("db:seed --class={$_model}TableSeeder");
            return response([
                'message' => "{$model} Populated'"
            ], 200);
        }catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }

        // php artisan db:seed --class=PostsTableSeeder

    }
}
