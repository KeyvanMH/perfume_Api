<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFactorRequest;
use App\Http\Requests\StorePerfumeBasedFactorRequest;
use App\Http\Requests\UpdateFactorRequest;
use App\Http\Requests\UpdatePerfumeBasedFactorRequest;
use App\Http\Resources\FactorResource;
use App\Models\Factor;
use App\Models\Perfume;
use App\Models\PerfumeBasedFactor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
/**
 * @OA\Get(
 *      path="/api/admin/factor",
 *      summary="گرفتن تمام کتگوری های موجود برای ادمین",
 *     @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          name="access token",
 *          in="header",
 *          required=true
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK",
 *           @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/AdminFactor")
 *               }
 *           )
 *       ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *       )
 *  )
 *
 * @OA\Get(
 *      path="/api/admin/factor/{id}",
 *      summary="گرفتن اطلاعات مربوط به یک فاکتور خاص برای ادمین",
 *     @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          name="access token",
 *          in="header",
 *          required=true
 *      ),
 *     @OA\Parameter(
 *          description="ایدی فاکتور مربوط در یو ار ال",
 *          name="id",
 *          in="path",
 *          required=true
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK",
 *           @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/AdminFactor")
 *               }
 *           )
 *       ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *       )
 *  )
 *
 * @OA\Get(
 *      path="/api/admin/factor/personal/{userId}",
 *      summary="گرفتن فاکتور های  مربوط به یک ادمین خاص",
 *     @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          name="access token",
 *          in="header",
 *          required=true
 *      ),
 *     @OA\Parameter(
 *          description="ایدی کاربر مربوط در یو ار ال",
 *          name="userId",
 *          in="path",
 *          required=true
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK",
 *           @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/AdminFactor")
 *               }
 *           )
 *       ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *       )
 *  )
 *
 * @OA\Post(
 *      path="/api/admin/factor",
 *      summary="اضافه کردن فاکتور",
 *      @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           in="header",
 *           name="access token",
 *           required=true
 *       ),
 *     @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="products",
 *                      type="array",
 *                      @OA\Items(
 *                          @OA\Property(
 *                              property="name",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="volume",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="price",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="quantity",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="slug",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="warranty",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="description",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="gender",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="percent",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="amount",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="start_date",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="end_date",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="discount_card",
 *                              type="string"
 *                          ),
 *                          @OA\Property(
 *                              property="discount_card_percent",
 *                              type="string"
 *                          )
 *                      )
 *                  ),
 *                  example={"products":{{"name":"256","volume":"29","quantity":"510","description":"this is fuking description","price":"1000000","slug":"lahg-asdgkjah-agh-askgh-lkjfg","gender":"sport","percent":"22.12","end_date":"2011-03-03 14:14"},{"name":"256","volume":"29","quantity":"510","description":"this is fuking description","price":"1000000","slug":"lahg-asdgkjah-agh-askgh-lkjfg","gender":"sport","percent":"22.12","end_date":"2011-03-03 14:14"}}}
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="OK"
 *       ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *       )
 *  )
 *
 * @OA\Delete(
 *      path="/api/admin/factor/{id}",
 *      summary="حذف فاکتور موجود و مشتقاتش (فقط سوپر ادمین و سازنده فاکتور توانایی پاک کردن آن را دارند)",
 *      @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          in="header",
 *          name="access token",
 *          required=true
 *      ),
 *      @OA\Parameter(
 *          description="ایدی فاکتور در یو ار ال",
 *          in="path",
 *          name="id",
 *          required=true
 *      ),
 *      @OA\Response(
 *           response=202,
 *           description="OK"
 *       ),
 *      @OA\Response(
 *           response=403,
 *           description="unAuthorized"
 *       )
 *  )
 *
 * @OA\Schema(
 *     schema="AdminFactor",
 *     title="همه فاکتور ها برای ادمین",
 *     @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *     @OA\Property(
 *          property="isActive",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="createdAt",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="updatedAt",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="userId",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="userName",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="userRole",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="perfumeBasedFactor",
 *          type="array",
 *          @OA\Items(
 *              @OA\Property(
 *                  property="id",
 *                  type="integer"
 *              ),
 *              @OA\Property(
 *                  property="name",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="volume",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="price",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="stock",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="sold",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="isActive",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="warranty",
 *                  type="string"
 *              )
 *          )
 *     )
 * )
 */

class FactorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FactorResource::collection(Factor::withTrashed()->with(['perfumeBasedFactor','user'])->paginate(15));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeBasedFactorRequest $request)
    {
        $products = $request->validated('products');
        DB::transaction(function () use ($products,$request) {
            // Add values to factor
            $factor = Factor::create([
                'user_id' => $request->user()->id,
            ]);
            foreach ($products as $product){
                // Check if the perfume exists
                $perfume = Perfume::where('slug', '=', $product['slug'])->first();
                if (is_null($perfume)) {
                    // Create new perfume if it doesn't exist
                    $perfume = Perfume::create([
                        'brand_id' => 1,
                        'category_id' => 1,
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'volume' => $product['volume'],
                        'description' => $product['description'],
                        'slug' => $product['slug'],
                        'warranty' => $product['warranty']??null,
                        'gender' => $product['gender'],
                        'discount_percent' => $product['percent'] ?? null,
                        'discount_amount' => $product['amount'] ?? null,
                        'discount_start_date' => $product['start_date']?? null,
                        'discount_end_date' => $product['end_date']?? null,
                        'discount_card' => $product['discount_card']?? null,
                        'discount_card_percent' => $product['discount_card_percent']?? null,
                    ]);
                } else {
                    // Update existing perfume quantity
                    //TODO think about how to manage discount cards and discounts
                    $perfume->quantity += $product['quantity'];
                    $perfume->save();
                }
                // Create perfume-based factor
                PerfumeBasedFactor::create([
                    'factor_id' => $factor->id,
                    'perfume_id' => $perfume->id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'volume' => $product['volume'],
                    'stock' => $product['quantity'],
                    'gender' => $product['gender'],
                    'warranty' => $product['warranty'] ?? null,
                ]);
            }
        });
        return response()->json(['response' => 'ok'],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $factor =  Factor::withTrashed()->with(['perfumeBasedFactor'])->where('id','=',$id)->firstOrFail();
        return new FactorResource($factor);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factor $factor)
    {
        if(!Gate::allows('manipulate_factor',$factor)){
            return response()->json(['response' => 'unauthenticated']);
        }
        $perfumesOfFactor = $factor->perfumeBasedFactor;
        DB::transaction(function ()use($factor,$perfumesOfFactor) {
            $factor->delete();
            foreach ($perfumesOfFactor as $perfume){
                $perfume->delete();

            }
        });
        return response()->json(['response' => 'ok']);
    }

    public function indexAdminFactor(User $user){
        if($user->role != 'product_admin' or $user->role != 'super_admin'){
            return response()->json(['response' => 'یوزر درخواستی ادمین نمیباشد'],404);
        }
        $factors = $user->factors()->with('perfumeBasedFactor')->paginate(15);
        return FactorResource::collection($factors);
    }

}
