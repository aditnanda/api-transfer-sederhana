<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="Dokumentasi API Transfer Sederhana, dibuat oleh Aditya Nanda Utama untuk keperluan seleksi BackEnd Programmer di bosCOD",
 *     version="1.0.0",
 *     title="Dokumentasi API Transfer Sederhana",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="aditya.nanda0030@gmail.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\PathItem(path="/api")
 * @OA\SecurityScheme(
 *     type="http",
 *     in="header",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     name="Authorization"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
