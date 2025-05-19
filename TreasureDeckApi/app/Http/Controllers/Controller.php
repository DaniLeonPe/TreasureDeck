<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *     title="TreasureDeck API",
 *     version="1.0.0"
 * )
 *
  * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación usando Bearer token"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
