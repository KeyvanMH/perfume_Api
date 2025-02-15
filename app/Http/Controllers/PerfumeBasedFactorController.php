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
            if($perfumeBasedFactor->is_active && !$perfumeBasedFactor->deleted_at && $perfumeBasedFactor->stock != $perfumeBasedFactor->sold){
                    $perfumeBasedFactor->delete();
                    $perfumeBasedFactor->perfume->quantity -= $perfumeBasedFactor->stock - $perfumeBasedFactor->sold;
                    $this->deactivatePerfumeBasedFactor($perfumeBasedFactor);
                    $perfumeBasedFactor->perfume->save();
            }
        });
        return response()->json(['response' => 'ok']);
    }
    private function deactivatePerfumeBasedFactor($perfumeBasedFactor):void{
        $perfumeBasedFactor->is_active = 0;
        $perfumeBasedFactor->save();
    }
}
