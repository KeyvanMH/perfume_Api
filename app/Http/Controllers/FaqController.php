<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * @OA\Get(
     *     path="api/faq",
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
     * @OA\Get(
     *      path="api/faq/admin",
     *      summary="سوالات متداول برای ادمین همراه با سوالات غیرفعال",
     *      @OA\Response(
     *           response=200,
     *           description="OK",
     *           @OA\JsonContent(
     *               oneOf={
     *                   @OA\Schema(ref="#/components/schemas/Faq"),
     *               },
     *           )
     *       )
     *  )
     *
     *
     *
     * @OA\Schema(
     *    schema="Faq",
     *    title="سوالات متداول",
     *   	@OA\Property(
     *         property="سوال",
     *         type="string"
     *     ),
     *   	@OA\Property(
     *         property="جواب",
     *         type="string"
     *     )
     *   )
     */


    public function index()
    {
        //TODO make a gate or policy to authorize admins
        return FaqResource::collection(Faq::where('is_active',true)->get());
    }


}
