<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminContactUsResource;
use App\Models\ContactUs;

/**
 * @OA\Get(
 *        path="/api/admin/contact-us",
 *        summary="گرفتن لیست تمام فرم 'ارتباط با ما' های موجود برای ادمین",
 *           @OA\Parameter(
 *               description="احراز هویت با توکن",
 *               in="path",
 *               name="access token",
 *               required=true,
 *           ),
 *        @OA\Response(
 *             response=200,
 *             description="OK",
 *             @OA\JsonContent(
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/admin_contact_us")
 *             )
 *         )
 *    )
 *
 * @OA\Delete(
 *         path="/api/admin/contact-us/{id}",
 *         summary="حذف فرم موجود",
 *             @OA\Parameter(
 *              description="احراز هویت با توکن",
 *              in="path",
 *              name="access token",
 *              required=true,
 *          ),@OA\Parameter(
 *              description="آیدی سوال در یو ار ال",
 *              in="path",
 *              name="id",
 *              required=true,
 *          ),
 *         @OA\Response(
 *              response=200,
 *              description="OK"
 *              ),
 *         @OA\Response(
 *              response=403,
 *              description="unAuthorized"
 *              ),
 *          )
 *     )
 *
 * @OA\Schema(
 *       schema="admin_contact_us",
 *       title="اطلاعات لیست ارتباط با ما",
 *       @OA\Property(
 *            property="phone_number",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="description",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="is_active",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="email",
 *            type="string"
 *        )
 *  )
 */
class AdminContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($status)
    {
        //TODO use cache
        return AdminContactUsResource::collection(ContactUs::withTrashed()->paginate(15));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactUs $contactUs)
    {
        $contactUs->delete();
        return response()->json(['response' => 'ok'],200);
    }
}
