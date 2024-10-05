<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfumeBasedFactorRequest;
use App\Http\Requests\UpdatePerfumeBasedFactorRequest;
use App\Http\Resources\FactorResource;
use App\Http\Resources\PerfumeBasedFactorResource;
use App\Models\Factor;
use App\Models\Perfume;
use App\Models\PerfumeBasedFactor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
/**
 * @OA\Get(
 *       path="/api/admin/factor-product/{id}",
 *       summary="گرفتن یک زیر کالا ",
 *      @OA\Parameter(
 *           description="احراز هویت با توکن",
 *           name="access token",
 *           in="header",
 *           required=true
 *       ), @OA\Parameter(
 *           description="ایدی در یو ار ال",
 *           name="access token",
 *           in="query",
 *           required=true
 *       ),
 *       @OA\Response(
 *            response=200,
 *            description="OK",
 *            @OA\JsonContent(
 *                oneOf={
 *                    @OA\Schema(ref="#/components/schemas/PerfumeBasedFactor")
 *                }
 *            )
 *        ),
 *       @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *        )
 *   )
 *
 *
 * @OA\Put(
 *       path="/api/admin/factor-product/{id}",
 *       summary="تغییر دادن عضوی از فاکتور اضافه شده ( فقط وضعیت فروش و تعداد قابل تغییر است)(فقط سوپر ادمین و سازنده فاکتور توانایی پاک کردن آن را دارند)",
 *       @OA\Parameter(
 *            description="احراز هویت با توکن",
 *            in="path",
 *            name="access token",
 *            required=true,
 *        ),
 *       @OA\Parameter(
 *           description="ایدی  در یو ال ال",
 *           in="path",
 *           name="id",
 *           required=true,
 *       ),
 *       @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="application/json",
 *               @OA\Schema(
 *                   @OA\Property(
 *                       property="stock",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="is_active",
 *                      enum={"true","false"}
 *                   ),
 *                   example={"is_active" :"false","stock":"-1"}
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *             response=200,
 *             description="OK"
 *             ),
 *       @OA\Response(
 *             response=403,
 *             description="unAuthorized"
 *             ),
 *         )
 *    )
 * @OA\Delete(
 *       path="/api/admin/factor-product/{id}",
 *      summary="حذف عضوری از  فاکتور موجود (فقط سوپر ادمین و سازنده فاکتور توانایی پاک کردن آن را دارند)",
 *      @OA\Parameter(
 *          description="احراز هویت با توکن",
 *          in="header",
 *          name="access token",
 *          required=true
    *      ),
 *      @OA\Parameter(
 *          description="ایدی عضو فاکتور در یو ار ال",
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
 * @OA\Schema (
 *     schema="PerfumeBasedFactor",
 * *     title="گرفتن یک زیر کالا",
 * *     @OA\Property(
 * *          property="id",
 * *          type="integer"
 * *      ), @OA\Property(
 * *          property="name",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="volume",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="price",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="stock",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="sold",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="isActive",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="warranty",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="gender",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="deleted",
 * *          type="string"
 * *      ), @OA\Property(
 * *          property="updatedAt",
 * *          type="integer"
 * *      ),
 * )
**/
class PerfumeBasedFactorController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $perfumeBasedFactor = PerfumeBasedFactor::withTrashed()->where('id','=',$id)->firstOrFail();
        return new PerfumeBasedFactorResource($perfumeBasedFactor);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerfumeBasedFactorRequest $request , $id)
    {
        //TODO dont make the column of perfume and perfume based factor negetive
        $perfumeBasedFactor = PerfumeBasedFactor::with(['perfume','factor'])->findOrFail($id);
        if(!Gate::allows('manipulate_factor',$perfumeBasedFactor->factor)){
            return response()->json(['response' => 'unauthenticated']);
        }
        if ($request->validated('stock') == "false" and $request->validated('is_active') == "true"){
            return response()->json(['response' => 'empty request']);
        }
        DB::transaction(function()use($request,$perfumeBasedFactor){
            if($request->validated('is_active') !== NULL){
                if ($request->validated('is_active') == 'true' and !$perfumeBasedFactor->is_active){
                    $perfumeBasedFactor->is_active = true;
                    $perfumeBasedFactor->perfume->quantity += $perfumeBasedFactor->stock - $perfumeBasedFactor->sold;
                }elseif ($request->validated('is_active') == "false" and $perfumeBasedFactor->is_active){
                    $perfumeBasedFactor->is_active = false;
                    $perfumeBasedFactor->perfume->quantity -= $perfumeBasedFactor->stock - $perfumeBasedFactor->sold;
                }
                $perfumeBasedFactor->save();
                $perfumeBasedFactor->perfume->save();
            }
            if($request->validated('stock')){
                $perfumeBasedFactor->stock += $request->validated('stock');
                if($request->validated('is_active') == "true"){
                    //add stock to the perfume table too
                    $perfumeBasedFactor->perfume->quantity += $request->validated('stock');
                    $perfumeBasedFactor->perfume->save();
                }
                $perfumeBasedFactor->save();
            }
        });
        return response()->json(['response' => 'ok']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perfumeBasedFactor = PerfumeBasedFactor::with(['factor','perfume'])->where('id','=',$id)->firstOrFail();
        if(!Gate::allows('manipulate_factor',$perfumeBasedFactor->factor)){
            return response()->json(['response' => 'unauthenticated']);
        }
        DB::transaction(function()use($perfumeBasedFactor){
            $perfumeBasedFactor->delete();
            $perfumeBasedFactor->perfume->quantity -= $perfumeBasedFactor->stock - $perfumeBasedFactor->sold;
            $perfumeBasedFactor->perfume->save();
        });
        return response()->json(['response' => 'ok']);
    }


}
