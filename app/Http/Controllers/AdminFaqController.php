<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Http\Resources\AdminFaqResource;
use App\Models\Faq;
/**
 * @OA\Get(
 *      path="/api/admin/faq",
 *      summary="سوالات متداول برای ادمین همراه با سوالات غیرفعال",
 *     @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          name="access token",
 *          in="path",
 *          required=true,
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK",
 *           @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/AdminFaq"),
 *               },
 *           )
 *       ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *           )
 *       )
 *  )
 *
 *
 *
 *
 * @OA\Post(
 *      path="/api/admin/faq",
 *      summary="افزودن به سوالات متداول",
 *          @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           in="path",
 *           name="access token",
 *           required=true,
 *       ),
 *     @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="question",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="answer",
 *                      type="string"
 *                  ),
 *                  example={"question": "سوال مورد نظر", "answer": "جواب مورد نظر "}
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK"
 *           ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *           ),
 *       )
 *  )
 *
 *
 *
 * @OA\Put(
 *       path="/api/admin/faq/{id}",
 *       summary="تغییر سوال موجود",
 *           @OA\Parameter(
 *            description="احراز هویت با توکن",
 *            in="path",
 *            name="access token",
 *            required=true,
 *        ),@OA\Parameter(
 *            description="آیدی سوال در روت",
 *            in="path",
 *            name="id",
 *            required=true,
 *        ),
 *      @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="application/json",
 *               @OA\Schema(
 *                   @OA\Property(
 *                       property="question",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="answer",
 *                       type="string"
 *                   ),
 *                   example={"question": "سوال مورد نظر", "answer": "جواب مورد نظر "}
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *            response=200,
 *            description="OK"
 *            ),
 *       @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            ),
 *        )
 *   )
 *
 *
 *
 *
 * @OA\Delete(
 *        path="/api/admin/faq/{id}",
 *        summary="حذف سوال موجود",
 *            @OA\Parameter(
 *             description="احراز هویت با توکن",
 *             in="path",
 *             name="access token",
 *             required=true,
 *         ),@OA\Parameter(
 *             description="آیدی سوال در روت",
 *             in="path",
 *             name="id",
 *             required=true,
 *         ),
 *        @OA\Response(
 *             response=200,
 *             description="OK"
 *             ),
 *        @OA\Response(
 *             response=403,
 *             description="unAuthorized"
 *             ),
 *         )
 *    )


 *
 *
 * @OA\Schema(
 *     schema="AdminFaq",
 *     title=" سوالات متداول ادمین",
 *    	@OA\Property(
 *          property="question",
 *          type="string"
 *      ),
 *    	@OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *    	@OA\Property(
 *          property="answer",
 *          type="string"
 *      ),
 *    	@OA\Property(
 *          property="is_active",
 *          type="string",
 *          default="فعال",
 *      )
 *    )
 */


class AdminFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return AdminFaqResource::collection(Faq::withTrashed()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        $user = Faq::create([
            'question' => $request->input('question'),
            'answer' => $request->input('answer'),
        ]);
        return AdminFaqResource::collection(collect([$user]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->question = $request->input('question');
        $faq->answer = $request->input('question');
        $faq->save();
        $faq->fill($request->only(['question', 'answer', 'is_active']))->save();
        return response()->json(['response' => 'ok'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['response' => 'ok'],200);
    }
}
