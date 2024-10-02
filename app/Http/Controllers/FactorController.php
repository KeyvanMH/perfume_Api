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

class FactorController extends Controller
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
        $factors = $user->factors()->with('perfumeBasedFactor')->get();

        return FactorResource::collection($factors);
    }

}
