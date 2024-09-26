<?php

namespace App\Http\Controllers;

use App\Http\Action\UserQuery;
use App\Http\Requests\StorePerfumeRequest;
use App\Http\Requests\UpdatePerfumeRequest;
use App\Http\Resources\PerfumeProductAdminResource;
use App\Http\Resources\PerfumeSearchAdminResource;
use App\Http\Resources\PerfumeSearchResource;
use App\Models\Perfume;
use Illuminate\Http\Request;

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
        //create factor and its perfumeBasedFactor and then add to the Perfume model



        Perfume::create([
            'name' => $request->validated('name'),
            'price' => $request->validated('price'),
            'volume' => $request->validated('volume'),
            'quantity' => $request->validated('quantity'),
            'description' => $request->validated('description'),
            'slug' => $request->validated('slug'),
            'warranty' => $request->validated('warranty'),
            'gender' => $request->validated('gender'),
            'percent' => $request->validated('percent')??NULL,
            'amount' => $request->validated('name')??NULL,
            'start_date' => $request->validated('name'),
            'end_date' => $request->validated('name'),
            'discount_card' => $request->validated('name'),


        ]);    }

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
        $perfume->delete();
        return response()->json(['response' => 'ok']);

    }
}
