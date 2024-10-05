<?php

namespace App\Http\Controllers;

use App\Http\Requests\destroyBrandImageRequest;
use App\Http\Requests\StoreBrandImageRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandAdminResource;
use App\Http\Resources\BrandFullAdminResource;
use App\Models\Brand;
use App\Models\BrandImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Get(
 *       path="/api/admin/brand",
 *       summary="گرفتن همه برند های موجود برای ادمین(از جمله برند های حذف شده)",
 *      @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           name="access token",
 *           in="path",
 *           required=true,
 *       ),
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                oneOf={
 *                    @OA\Schema(ref="#/components/schemas/adminBrand"),
 *                },
 *            )
 *        ),
 *       @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            )
 *        )
 *   )
 *
 * @OA\Get(
 *       path="/api/admin/brand/{slug}",
 *       summary="گرفتن اطلاهات یک برند برای ادمین",
 *      @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           name="access token",
 *           in="path",
 *           required=true,
 *       ),
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                oneOf={
 *                    @OA\Schema(ref="#/components/schemas/adminBrandFull"),
 *                },
 *            )
 *        ),
 *       @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            )
 *        )
 *   )
 *
 * @OA\Post(
 *       path="/api/admin/brand/image/{brand:slug}",
 *       summary="افزودن عکس به برندی خاص",
 *           @OA\Parameter(
 *            description="احراز هویت با توکن",
 *            in="path",
 *            name="access token",
 *            required=true,
 *        ),
 *      @OA\Parameter(
 *            description="اسلاگ در یو ار ال",
 *            in="path",
 *            name="slug",
 *            required=true,
 *        ),
 *      @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="multipart/form-data",
 *                    @OA\Schema(
 *                        @OA\Property(
 *                              property="images",
 *                              type="array",
 *                              @OA\Items(
 *                                  type="string",
 *                                  format="binary"
 *                              )
 *                        )
 *                    )
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
 * @OA\Post(
 *       path="/api/admin/brand",
 *       summary="افزودن یک برند",
 *           @OA\Parameter(
 *            description="احراز هویت با توکن",
 *            in="path",
 *            name="access token",
 *            required=true,
 *        ),
 *      @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="multipart/form-data",
 *               @OA\Schema(
 *                   @OA\Property(
 *                       property="logo",
 *                       type="string",
 *                       format="binary"
 *                   ),
 *                   @OA\Property(
 *                       property="slug",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="link",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="description",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="title",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="images",
 *                       type="array",
 *                       @OA\Items(
 *                           type="string",
 *                           format="binary"
 *                       )
 *                   ),
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *            response=201,
 *            description="OK"
 *            ),
 *       @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            ),
 *        )
 *   )
 *
 * @OA\Put(
 *        path="/api/admin/brand/{brand:slug}",
 *        summary="تغییر برند موجود بدون تغییر عکس(برای افزودن یا حذف عکس برند روت مخصوص داریم)",
 *            @OA\Parameter(
 *             description="احراز هویت با توکن",
 *             in="path",
 *             name="access token",
 *             required=true,
 *         ),
 *         @OA\Parameter(
 *             description="اسلاگ سوال در یو ار ال",
 *             in="path",
 *             name="slug",
 *             required=true,
 *         ),
 *       @OA\RequestBody(
 *                @OA\MediaType(
 *                mediaType="multipart/form-data",
 *                @OA\Schema(
 *                    @OA\Property(
 *                        property="logo",
 *                        type="string",
 *                        format="binary"
 *                    ),
 *                    @OA\Property(
 *                        property="slug",
 *                        type="string"
 *                    ),
 *                    @OA\Property(
 *                        property="link",
 *                        type="string"
 *                    ),
 *                    @OA\Property(
 *                        property="description",
 *                        type="string"
 *                    ),
 *                    @OA\Property(
 *                        property="title",
 *                        type="string"
 *                    )
 *                )
 *            )
 *        ),
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
 * @OA\Delete(
 *         path="/api/admin/brand/image",
 *         summary="حذف عکس برند",
 *             @OA\Parameter(
 *              description="احراز هویت با توکن",
 *              in="path",
 *              name="access token",
 *              required=true,
 *          ),
 *           @OA\Parameter(
 *              description="ایدی عکس در روت",
 *              name="ایدی عکس",
 *              in="path",
 *              required=true,
 *          ),
 *         @OA\Response(
 *              response=204,
 *              description="OK"
 *              ),
 *         @OA\Response(
 *              response=403,
 *              description="unAuthorized"
 *              ),
 *          )
 *     )
 *
 * @OA\Delete(
 *         path="/api/admin/brand/{brand:slug}",
 *         summary="حذف برند موجود",
 *             @OA\Parameter(
 *              description="احراز هویت با توکن",
 *              in="path",
 *              name="access token",
 *              required=true,
 *          ),
 *         @OA\Parameter(
 *              description="اسلاگ برند در یو ار ال",
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
 *       schema="adminBrand",
 *       title="  همه برند های مخصوص ادمین",
 *       @OA\Property(
 *            property="id",
 *            type="integer"
 *        ),
 *       @OA\Property(
 *            property="logo_path",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="link",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="description",
 *            type="string",
 *        ),
 *       @OA\Property(
 *            property="title",
 *            type="string",
 *        ),
 *       @OA\Property(
 *            property="slug",
 *            type="string",
 *        ),
 *       @OA\Property(
 *            property="status",
 *            type="string",
 *        )
 *  )
 * @OA\Schema(
 *      schema="adminBrandFull",
 *      title=" برند  مخصوص ادمین",
 *      @OA\Property(
 *           property="id",
 *           type="integer"
 *       ),
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
 *           type="string",
 *       ),
 *      @OA\Property(
 *           property="title",
 *           type="string",
 *       ),
 *      @OA\Property(
 *           property="slug",
 *           type="string",
 *       ),
 *      @OA\Property(
 *           property="status",
 *           type="string",
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
 *     )
 */
class BrandAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BrandAdminResource::collection(Brand::withTrashed()->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        DB::transaction(function () use ($request) {
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logo', 'public') : null;

            $brand = Brand::create([
                'description' => $request->input('description'),
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'logo_path' => $logoPath,
                'link' => $request->input('link') ?? null,
            ]);

            if ($request->hasFile('images')) {
                $imagesData = collect($request->file('images'))->map(function ($image) use ($brand) {
                    return [
                        'image_path' => $image->store('brandsImage', 'public'),
                        'alt' => $image->getClientOriginalName(),
                        'extension' => $image->extension(),
                        'size' => $image->getSize(),
                        'brand_id' => $brand->id,
                    ];
                });

                $brand->image()->createMany($imagesData);
            }

        });
            return response()->json(['response' => 'ok'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        // we wont use route model binding because we cant have deleted model with route model binding
        $result = Brand::withTrashed()->where('slug','=',$slug)->with('image')->get();
        return BrandFullAdminResource::collection($result);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        if($request->validated('logo')){
            if(Storage::exists('public/'.$brand->logo_path)){
                Storage::delete('public/'.$brand->logo_path);
            }
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logo', 'public') : null;
            $brand->logo_path = $logoPath;
        }

        $fieldsToUpdate = ['slug', 'link', 'description', 'title'];
        foreach ($fieldsToUpdate as $field) {
            if ($request->filled($field)) {
                $brand->$field = $request->validated($field);
            }
        }
        $brand->save();
        return response()->json(['response' => 'ok'], 200);
    }
    /**
     * add Images for specific brand
     */
    public function storeImage(StoreBrandImageRequest $request , Brand $brand) {
        if($request->validated('images')) {
            DB::transaction(function () use ($request, $brand) {
                $imagesData = collect($request->file('images'))->map(function ($image) use ($brand) {
                    return [
                        'image_path' => $image->store('brandsImage', 'public'),
                        'alt' => $image->getClientOriginalName(),
                        'extension' => $image->extension(),
                        'size' => $image->getSize(),
                        'brand_id' => $brand->id,
                    ];
                });

                $brand->image()->createMany($imagesData);
            });
            return response()->json(['response' => 'ok'], 200);
        }else{
            return response()->json(['response' => 'عکسی یافت نشد'], 204);
        }

    }
    /**
     * remove specific Image
     */
    public function destroyImage(destroyBrandImageRequest $request,BrandImage $brandImage) {
        //check the input for the image to exist in DB , delete the image and index in table, return response
        if(Storage::exists('public/'.$request->validated('imageName'))){
            Storage::delete('public/'.$request->validated('imageName'));
        }
        $brandImage->delete();
        return response()->json(['response' => 'ok'],200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(['response' => 'ok'],200);
    }
}
