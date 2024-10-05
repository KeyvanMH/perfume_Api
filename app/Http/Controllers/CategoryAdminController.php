<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryAdminResource;
use App\Http\Resources\CategoryFullAdminResource;
use App\Models\Category;
/**
 * @OA\Get(
 *      path="/api/admin/category",
 *      summary="گرفتن تمام کتگوری های موجود برای ادمین",
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
 *                   @OA\Schema(ref="#/components/schemas/AdminCategory"),
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
 * @OA\Get(
 *      path="/api/admin/category/{slug}",
 *      summary="گرفتن اطلاعات مربوط به یک کتگوری خاص برای ادمین",
 *     @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          name="access token",
 *          in="path",
 *          required=true,
 *      ),
 *     @OA\Parameter(
 *          description="اسلاگ کتگوری مربوط در یو ار ال",
 *          name="اسلاگ در یو ار ال",
 *          in="path",
 *          required=true,
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK",
 *           @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/AdminFullCategory"),
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
 *      path="/api/admin/category",
 *      summary="اضافه کردن کتگوری ",
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
 *                      property="name",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="type",
 *                      enum={"perfume", "watch"},
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="description",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="slug",
 *                      type="string"
 *                  ),
 *                  example={"name": "woody orbal category", "type": "perfume" ,"description": "مخصوص فصل های گرم " , "slug": "woody-orbal"}
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
 *       path="/api/admin/category/{category:slug}",
 *       summary="تغییر کتگوری موجود",
 *           @OA\Parameter(
 *            description="احراز هویت با توکن",
 *            in="path",
 *            name="access token",
 *            required=true,
 *        ),@OA\Parameter(
 *            description="اسلاگ سوال در روت",
 *            in="path",
 *            name="id",
 *            required=true,
 *        ),
 *      @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="application/json",
 *               @OA\Schema(
 *                   @OA\Property(
 *                       property="name",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="type",
 *                       enum={"perfume", "watch"},
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="description",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="slug",
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
 *        path="/api/admin/category/{category:slug}",
 *        summary="حذف کتگوری موجود",
 *            @OA\Parameter(
 *             description="احراز هویت با توکن",
 *             in="path",
 *             name="access token",
 *             required=true,
 *         ),@OA\Parameter(
 *             description="اسلاگ سوال در روت",
 *             in="path",
 *             name="id",
 *             required=true,
 *         ),
 *        @OA\Response(
 *             response=202,
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
 *     schema="AdminCategory",
 *     title="  کتگوری ادمین",
 *    	@OA\Property(
 *          property="name",
 *          type="string"
 *      ),
 *    	@OA\Property(
 *          property="type",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="slug",
 *          type="string"
 *      ),
 *    	@OA\Property(
 *          property="is_active",
 *          type="string",
 *          default="فعال",
 *      )
 *    )
 *
 *
 * @OA\Schema(
 *     schema="AdminFullCategory",
 *     title="   یک کتگوری برای ادمین",
 *    	@OA\Property(
 *          property="name",
 *          type="string"
 *      ),
 *    	@OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *    	@OA\Property(
 *          property="type",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="description",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="slug",
 *          type="string"
 *      ),
 *    	@OA\Property(
 *          property="is_active",
 *          type="string",
 *          default="فعال",
 *      )
 *    )
 */

class CategoryAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return CategoryAdminResource::collection(Category::withTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create([
            'name' => $request->validated('name'),
            'type' => $request->validated('type'),
            'description' => $request->validated('description'),
            'slug' => $request->validated('slug')
        ]);
        return response()->json(['responses' => 'ok'],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $result = Category::withTrashed()->where('slug','=',$slug)->get();
        return  CategoryFullAdminResource::collection($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category , UpdateCategoryRequest $request)
    {
        $fieldsToUpdate = ['name', 'type', 'description', 'slug'];
        foreach ($fieldsToUpdate as $field) {
            if ($request->filled($field)) {
                $category->$field = $request->validated($field);
            }
        }
        $category->save();
        return response()->json(['responses' => 'ok'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['responses' => 'ok'],200);
    }
}
