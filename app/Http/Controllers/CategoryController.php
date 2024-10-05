<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryFullResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

/**
 * @OA\Get(
 *       path="/api/category",
 *       summary="گرفتن لیست تمام کتگوری های موجود برای کاربر",
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                type="array",
 *                @OA\Items(ref="#/components/schemas/category")
 *            )
 *        )
 *   )
 *
 * @OA\Get(
 *       path="/api/category/{category:slug}",
 *       summary="گرفتن اطلاعات کامل مربوط کتگوری خاص",
 *       description="اسلاگ مربوط به برند در یو ار ال  شود ",
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                ref="#/components/schemas/full_category"
 *            )
 *        )
 *   )
 *
 * @OA\Schema(
 *      schema="category",
 *      title="کتگوری های موجود برای کاربر",
 *      @OA\Property(
 *           property="name",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="type",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="slug",
 *           type="string"
 *       )
 * )
 *
 * @OA\Schema(
 *      schema="full_category",
 *      title="اطلاعات کامل کتگوری",
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
 */

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache and pagination
        return CategoryResource::collection(Category::all());
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryFullResource($category);
    }


}
