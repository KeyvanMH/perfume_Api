<?php

namespace App\Http\Controllers;

use App\Http\Resources\PerfumeProductResource;
use App\Http\Resources\PerfumeSearchResource;
use App\Models\Perfume;
use Illuminate\Http\Request;
use App\Http\Action\UserQuery;
use Morilog\Jalali\Jalalian;

/**
*
 *   @OA\Get(
 *      path = "/api/search",
 *      summary = " گرفتن محصولات موجود با توجه به فیلتر برای کاربر",
 *      description="category[eq][0]=woody&category[eq][1]=orbalهر پارامتر فیلتر میتواند چند بار تکرار شود مثلا با تکرار کردن فیلتر کنگوری هر دو کنگوری سرچ شده به نتایج اضافه میشوند، برای هر فیلد تکراری کلید ارایه بعد جدید استفاده گنید مانند ",
 *      @OA\Parameter(
 *          name = "name[eq]",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس نام",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "quantity[gt] or quantity[eq] or quantity[lt]",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس تعداد",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "price[gt] or price[eq] or price[lt]",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس قیمت",
 *          @OA\Schema(
 *              type = "number"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "volume[gt] or volume[eq] or volume[lt] ",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس حجم عطر",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "warranty[eq] = true",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس گارانتی داشتن یا نداشتن (در صورت پر کردن  فیلد گارانتی ، فقط محصولات با گارانتی به نتایج می ایند،برای گرفتن همه محصولات این فیلد را خالی بگدارید ",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "gender[eq]",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس جنسیت عطر",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "brand[eq]",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس برند",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "category[eq]",
 *          in = "query",
 *          required = false,
 *          description = "فیلتر بر اساس کتگوری",
 *          @OA\Schema(
 *              type = "string"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "priceAsc",
 *          in = "query",
 *          required = false,
 *          description = "رده بندی بر اساس گران ترین",
 *          @OA\Schema(
 *              type = "boolean"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "priceDesc",
 *          in = "query",
 *          required = false,
 *          description = "رده بندی بر اساس ارزان ترین",
 *          @OA\Schema(
 *              type = "boolean"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "newest",
 *          in = "query",
 *          required = false,
 *          description = "رده بندی بر اساس جدید ترین",
 *          @OA\Schema(
 *              type = "boolean"
    *          )
 *      ),
 *      @OA\Parameter(
 *          name = "sold",
 *          in = "query",
 *          required = false,
 *          description = "رده بندی بر اساس پر فروش ترین",
 *          @OA\Schema(
 *              type = "boolean"
    *          )
 *      ),
 *      @OA\Response(
 *          response = 200,
 *          description = "Successful search",
 *          @OA\JsonContent(
 *              type = "array",
 *              @OA\Items(ref = "#/components/schemas/search")
*          )
 *      )
 *  )
 *
 *
 *
 *
 * @OA\Get(
 *       path="/api/perfume/{product:slug}",
 *       summary="گرفتن اطلاعات کامل مربوط محصول خاص",
 *       description="اسلاگ مربوط به برند در یو ار ال  شود ",
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                ref="#/components/schemas/full_product"
 *            )
 *        )
 *   )
 *
 *
 *
 * @OA\Schema(
 *      schema="full_product",
 *      title="اطلاعات محصول موجود برای کاربر",
 *      @OA\Property(
 *           property="name",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="price",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="volume",
 *           type="string"
 *       ),
 *     @OA\Property(
 *           property="quantity",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="warranty",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="description",
 *           type="string"
 *       ),
 *     @OA\Property(
 *           property="gender",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="percent",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="amount",
 *           type="string"
 *       ),
 *     @OA\Property(
 *           property="discountEndTime",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="slug",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="category",
 *           type="string"
 *       ),
 *     @OA\Property(
 *           property="brand",
 *           type="string"
 *       ),
 * )
 *
 * @OA\Schema(
 *      schema="search",
 *      title="محصولات فیلتر شده",
 *      @OA\Property(
 *           property="name",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="price",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="volume",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="quantity",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="warranty",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="gender",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="percent",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="slug",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="category name",
 *           type="string"
 *       ),
 *      @OA\Property(
 *           property="brand name",
 *           type="string"
 *       )
 * )
 */

class PerfumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //TODO calculate the discount in resource class
        $obj = new UserQuery($request->query());
        $obj->sanitize();
        $array = $obj->arrayBuilder();
        $result = $obj->queryBuilder($array);
//        return PerfumeSearchResource::collection($result->appends($request->query()));
        return PerfumeSearchResource::collection($result);
    }


    /**
     * Display the specified resource.
     */
    public function show(Perfume $perfume)
    {
        return PerfumeProductResource::collection($perfume);
    }


}
