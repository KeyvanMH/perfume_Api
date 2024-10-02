<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfumeBasedFactorRequest;
use App\Http\Requests\UpdatePerfumeBasedFactorRequest;
use App\Http\Resources\FactorResource;
use App\Models\Factor;
use App\Models\Perfume;
use App\Models\PerfumeBasedFactor;
use App\Rules\SlugRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PerfumeBasedFactorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FactorResource::collection(Factor::withTrashed()->with(['perfumeBasedFactor','user'])->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeBasedFactorRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Add values to factor
            $factor = Factor::create([
                'user_id' => $request->user()->id,
            ]);

            // Check if the perfume exists
            $perfume = Perfume::where('slug', '=', $request->validated('slug'))->first();

            if (is_null($perfume)) {
                // Create new perfume if it doesn't exist
                $perfume = Perfume::create([
                    'name' => $request->validated('name'),
                    'price' => $request->validated('price'),
                    'quantity' => $request->validated('quantity'),
                    'volume' => $request->validated('volume'),
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
            } else {
                // Update existing perfume quantity
                $perfume->quantity += $request->validated('quantity');
                $perfume->save();
            }

            // Create perfume-based factor
            PerfumeBasedFactor::create([
                'factor_id' => $factor->id,
                'perfume_id' => $perfume->id,
                'name' => $request->validated('name'),
                'price' => $request->validated('price'),
                'volume' => $request->validated('volume'),
                'quantity' => $request->validated('quantity'),
                'description' => $request->validated('description')??NULL,
                'gender' => $request->validated('gender'),
                'warranty' => $request->validated('warranty') ?? null,
            ]);
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
        return ['response' => 'ok'];
    }


}
