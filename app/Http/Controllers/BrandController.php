<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandFullResource;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
/**
 * @OA\Get(
 *       path="/api/brand",
 *       summary="گرفتن لیست تمام برند های موجود برای کاربر",
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                type="array",
 *                @OA\Items(ref="#/components/schemas/brand")
 *            )
 *        )
 *   )
 *
 * @OA\Get(
 *       path="/api/brand/{brand:slug}",
 *       summary="گرفتن اطلاعات کامل مربوط برند خاص",
 *       description="اسلاگ مربوط به برند در یو ار ال  شود ",
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                ref="#/components/schemas/full_brand"
 *            )
 *        )
 *   )
 *
 * @OA\Schema(
 *      schema="brand",
 *      title="برند های موجود برای کاربر",
 *      @OA\Property(
 *           property="logo_path",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="link",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="description",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="title",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="slug",
 *           type="string"
 *       )
 * )
 *
 * @OA\Schema(
 *      schema="full_brand",
 *      title="اطلاعات کامل برند",
 *      @OA\Property(
 *           property="logo_path",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="link",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="description",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="title",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="slug",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="image",
 *           type="array",
 *           @OA\Items(
 *               @OA\Property(
 *                   property="image_path",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="alt",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="extension",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="size",
 *                   type="string"
 *               )
 *           )
 *       )
 * )
 */


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return BrandResource::collection(Brand::with('images')->paginate(15));
    }



    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new BrandFullResource($brand->load('image'));
    }


}
