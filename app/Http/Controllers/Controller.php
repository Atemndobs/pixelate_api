<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   version="1.0.2",
 *   title="Pixelate",
 *   description="Documentation for Pixelate app",
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="Server"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="apiKey",
 *   name="Authorization",
 *   in="header",
 *   description="Example of Auth Header Token (Copy and paste below to authorize)=>
Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYwNTM0ODU1OSwiZXhwIjoxNjA1MzUyMTU5LCJuYmYiOjE2MDUzNDg1NTksImp0aSI6IjlqMDN0MVVISThoNjlhRUsiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.dn45EdZMatpW2zAQcZklDS2902mwVNx44TbkDiiGLXY",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

