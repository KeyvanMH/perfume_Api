<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\Faq;
/**
 * @OA\Get(
 *     path="/api/faq",
 *     summary="سوالات متداول برای کاربر عادی",
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              oneOf={
 *                  @OA\Schema(ref="#/components/schemas/Faq"),
 *              },
 *          )
 *      )
 * )
 *
 * @OA\Schema(
 *    schema="Faq",
 *    title="سوالات متداول",
 *   	@OA\Property(
 *         property="question",
 *         type="string"
 *     ),
 *   	@OA\Property(
 *         property="answer",
 *         type="string"
 *     )
 *   )
 **/
class FaqController extends Controller
{
    public function index()
    {
        //TODO use cache
        return FaqResource::collection(Faq::all());
    }
}
