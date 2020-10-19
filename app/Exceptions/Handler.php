<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  \Throwable  $exception
     * @return Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof AuthorizationException && $request->expectsJson()){
                return response()->json(["errors" => [
                        "message" => "Your are not authorized to access this resource"
                    ]], 403);
        }

        if ($exception instanceof ModelNotFoundException ){
                     return response()->json(["errors" => [
                    "message" => "Results was not found in Database"
                ]], 404);
        }
        if ($exception instanceof ModelNotDefined ){
                     return response()->json(["errors" => [
                    "message" => "No Model Defined"
                ]], 500);
        }
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode === 1451) {
                return $this->errorResponse(
                    'Cannot remove this resource permanently. It is related with another resource',
                    409
                );
            }
        }
        return parent::render($request, $exception);
    }
}
