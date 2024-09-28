<?php

namespace App\Http\Controllers;

use App\Http\Action\UserQuery;
use App\Http\Requests\StorePerfumeRequest;
use App\Http\Requests\UpdatePerfumeRequest;
use App\Http\Resources\PerfumeProductAdminResource;
use App\Http\Resources\PerfumeSearchAdminResource;
use App\Http\Resources\PerfumeSearchResource;
use App\Models\Factor;
use App\Models\Perfume;
use App\Models\PerfumeBasedFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeRequest $request)
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
                'slug' => $request->validated('slug'),
                'gender' => $request->validated('gender'),
                'warranty' => $request->validated('warranty') ?? null,
            ]);
        });
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
