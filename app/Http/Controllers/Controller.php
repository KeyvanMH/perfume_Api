<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel perfume api",
 *      description="L5 Swagger OpenApi ",
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 * )
 *
 * @OA\Tag(
 *     name="Projects",
 *     description="API Endpoints of Projects"
 * )
 */
abstract class Controller
{
    //TODO change user verification to phone number instead of email
    //TODO change reset password from from email to phone number

}
