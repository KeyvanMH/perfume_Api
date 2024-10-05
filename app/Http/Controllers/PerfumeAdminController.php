<?php

namespace App\Http\Controllers;

use App\Http\Action\UserQuery;
use App\Http\Requests\StorePerfumeRequest;
use App\Http\Requests\UpdatePerfumeRequest;
use App\Http\Resources\PerfumeOfPerfumeBasedFactor;
use App\Http\Resources\PerfumeProductAdminResource;
use App\Http\Resources\PerfumeSearchAdminResource;
use App\Models\Perfume;
use Illuminate\Http\Request;
/**
 *
 * @OA\Get(
 *       path="/api/admin/search",
 *       summary="گرفتن محصولات موجود با توجه به فیلتر برای ادمین(ادمین کالا های حذف شده هم میبیند)",
 *       description="category[eq][0]=woody&category[eq][1]=orbal هر پارامتر فیلتر میتواند چند بار تکرار شود مثلا با تکرار کردن فیلتر کنگوری هر دو کنگوری سرچ شده به نتایج اضافه میشوند، برای هر فیلد تکراری کلید ارایه بعد جدید استفاده کنید.",
 *       @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           name="access token",
 *           in="path",
 *           required=true,
 *       ),
 *       @OA\Parameter(
 *           name="name[eq]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس نام",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="quantity[gt] or quantity[eq] or quantity[lt]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس تعداد",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="price[gt] or price[eq] or price[lt]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس قیمت",
 *           @OA\Schema(
 *               type="number"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="volume[gt] or volume[eq] or volume[lt]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس حجم عطر",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="warranty[eq]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس گارانتی داشتن یا نداشتن (در صورت پر کردن فیلد گارانتی، فقط محصولات با گارانتی به نتایج می‌آیند، برای گرفتن همه محصولات این فیلد را خالی بگذارید)",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="gender[eq]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس جنسیت عطر",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="brand[eq]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس برند",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="category[eq]",
 *           in="query",
 *           required=false,
 *           description="فیلتر بر اساس کتگوری",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="priceAsc",
 *           in="query",
 *           required=false,
 *           description="رده بندی بر اساس گران ترین",
 *           @OA\Schema(
 *               type="boolean"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="priceDesc",
 *           in="query",
 *           required=false,
 *           description="رده بندی بر اساس ارزان ترین",
 *           @OA\Schema(
 *               type="boolean"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="newest",
 *           in="query",
 *           required=false,
 *           description="رده بندی بر اساس جدید ترین",
 *           @OA\Schema(
 *               type="boolean"
 *           )
 *       ),
 *       @OA\Parameter(
 *           name="sold",
 *           in="query",
 *           required=false,
 *           description="رده بندی بر اساس پر فروش ترین",
 *           @OA\Schema(
 *               type="boolean"
 *           )
 *       ),
 *       @OA\Response(
 *           response=200,
 *           description="Successful search",
 *           @OA\JsonContent(
 *               type="array",
 *               @OA\Items(ref="#/components/schemas/searchAdmin")
 *           )
 *       )
 *   )
 *
 * @OA\Get(
 *        path="/api/admin/perfume/{product:slug}",
 *        summary="گرفتن اطلاعات کامل مربوط محصول خاص برای ادمین(قابلیت دیدن محصولات حذف شده یا غیر فعال)",
 *        description="اسلاگ مربوط به برند در یو ار ال شود",
 *        @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           name="access token",
 *           in="path",
 *           required=true,
 *       ),
 *        @OA\Response(
 *             response=200,
 *             description="OK",
 *             @OA\JsonContent(
 *                 ref="#/components/schemas/fullProduct"
 *             )
 *         ),
 *           @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            )
 *        )
 *    )
 *
 * @OA\Get(
 *      path="/api/admin/perfume/based-factor/{slug}",
 *      summary="ادمین قابلیت این را دارد که تمام محصولات از فاکتور های مختلف که محصول ورودی را ارایه میکنند را ببیند",
 *      @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          name="access token",
 *          in="path",
 *          required=true,
 *      ),
 *      @OA\Parameter(
 *          description="اسلاگ مربوط به محصول در یو ار ال",
 *          name="اسلاگ مربوطه",
 *          in="path",
 *          required=true,
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK",
 *           @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/AdminPerfumeBasedFactorPerfumes"),
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
 * @OA\Post(
 *      path="/api/admin/perfume",
 *      summary="اضافه کردن کالا",
 *      @OA\Parameter(
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
 *                      property="price",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="volume",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="slug",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="warranty",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="description",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="gender",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="percent",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="amount",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="start_date",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="end_date",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="discount_card",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="discount_card_percent",
 *                      type="string"
 *                  ),
 *                  example={"name": "tom ford black orchid", "price": "20000" ,"volume": "50" , "slug": "tom-ford-black-orchid" , "warranty": "" , "description": "such a cool perfume" , "gender": "male" , "percent": "12.12" , "amount": "" , "start_date": "2024-03-06 17:00:00" , "end_date": "2024-04-06 17:00:00" , "discount_card": "perfume1234" , "discount_card_percent": "50.00"}
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
 * @OA\Put(
 *      path="/api/admin/perfume/{perfume:slug}",
 *      summary="تغییر دادن کالا (قیلد ها دل خواه هستند و هر کدام میتوانند خالی باشند)",
 *      @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           in="path",
 *           name="access token",
 *           required=true,
 *       ),
 *      @OA\Parameter(
 *          description="اسلاگ  در یو ال ال",
 *          in="path",
 *          name="id",
 *          required=true,
 *      ),
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="name",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="price",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="volume",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="slug",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="warranty",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="description",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="gender",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="percent",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="amount",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="start_date",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="end_date",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="discount_card",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="discount_card_percent",
 *                      type="string"
 *                  ),
 *                  example={"name": "tom ford black orchid", "price": "20000" ,"volume": "50" , "slug": "tom-ford-black-orchid" , "warranty": "" , "description": "such a cool perfume" , "gender": "male" , "percent": "12.12" , "amount": "" , "start_date": "2024-03-06 17:00:00" , "end_date": "2024-04-06 17:00:00" , "discount_card": "perfume1234" , "discount_card_percent": "50.00"}
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *            response=200,
 *            description="OK"
 *            ),
 *      @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            ),
 *        )
 *   )
 *
 * @OA\Delete(
 *        path="/api/admin/perfume/{perfume:slug}",
 *        summary="حذف کالا موجود",
 *            @OA\Parameter(
 *             description="احراز هویت با توکن",
 *             in="path",
 *             name="access token",
 *             required=true,
 *         ),
 *          @OA\Parameter(
 *             description="اسلاگ  در یو ال ال",
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
 * @OA\Schema(
 *       schema="fullProduct",
 *       title="اطلاعات محصول موجود برای ادمین",
 *       @OA\Property(
 *            property="name",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="price",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="volume",
 *            type="string"
 *        ),
 *      @OA\Property(
 *            property="quantity",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="warranty",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="description",
 *            type="string"
 *        ),
 *      @OA\Property(
 *            property="gender",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="percent",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="amount",
 *            type="string"
 *        ),
 *      @OA\Property(
 *            property="discountEndTime",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="slug",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="category",
 *            type="string"
 *        ),
 *      @OA\Property(
 *            property="brand",
 *            type="string"
 *        ),
 *  )
 *
 * @OA\Schema(
 *       schema="searchAdmin",
 *       title="محصولات فیلتر شده",
 *       @OA\Property(
 *            property="name",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="price",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="volume",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="quantity",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="warranty",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="gender",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="percent",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="slug",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="category name",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="brand name",
 *            type="string"
 *        )
 *  )
 *
 * @OA\Schema(
 *       schema="AdminPerfumeBasedFactorPerfumes",
 *       title="تمام محصولات ارایه دهنده یک محصول",
 *       @OA\Property(
 *            property="id",
 *            type="integer"
 *        ),
 *       @OA\Property(
 *            property="name",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="price",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="volume",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="quantity",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="warranty",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="gender",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="is_active",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="sold",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="createdAt",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="discountPercent",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="discountEndTime",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="discountCard",
 *            type="string"
 *        ),
 *       @OA\Property(
 *            property="perfumeBasedFactor",
 *            type="array",
 *            @OA\Items(
 *                @OA\Property(
 *                    property="id",
 *                    type="integer"
 *                ),
 *                @OA\Property(
 *                    property="name",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="volume",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="price",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="stock",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="sold",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="isActive",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="warranty",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="gender",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="deleted",
 *                    type="string"
 *                ),
 *                @OA\Property(
 *                    property="updatedAt",
 *                    type="string"
 *                ),
 *            )
 *        )
 *  )
 */

class PerfumeAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //TODO calculate the discount
        //TODO change the UserQuery class for user to be able to see deleted perfumes
        $obj = new UserQuery($request->query());
        $obj->sanitize();
        $array = $obj->arrayBuilder();
        $result = $obj->queryBuilder($array);
        return PerfumeSearchAdminResource::collection($result);
    }


    /**
     * Display a listing of the FactorPerfumes created the selling perfume.
     */
    public function indexBasedFactor($slug){
        $perfume =  Perfume::withTrashed()->where('slug','=',$slug)->with('perfumeBasedFactor')->paginate(15);
        return PerfumeOfPerfumeBasedFactor::collection($perfume);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeRequest $request)
    {
        $perfume = Perfume::create([
            'name' => $request->validated('name'),
            'price' => $request->validated('price'),
            'volume' => $request->validated('volume'),
            'quantity' => $request->validated('quantity'),
            'description' => $request->validated('description'),
            'slug' => $request->validated('slug'),
            'warranty' => $request->validated('warranty'),
            'gender' => $request->validated('gender'),
            'percent' => $request->validated('percent') ?? null,
            'amount' => $request->validated('amount') ?? null,
            'start_date' => $request->validated('start_date'),
            'end_date' => $request->validated('end_date'),
            'discount_card' => $request->validated('discount_card'),
        ]);
        return response()->json(['response' => 'ok'],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $result = Perfume::withTrashed()->where('slug','=',$slug)->get();
        return  PerfumeProductAdminResource::collection($result);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerfumeRequest $request, Perfume $perfume)
    {
        //TODO change category and brand
        return $perfume->updateOrFail($request->validated());
        //TODO change to resposne after checking if it works
//        return response()->json(['response' => 'ok']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfume $perfume)
    {
        //TODO check if is it possible to delete data because of the sold table restrict
        $perfume->delete();
        return response()->json(['response' => 'ok']);

    }
}
