<?php

namespace App\Http\Controllers;

use App\Http\Action\Filter\PerfumeFilter;
use App\Http\Action\Filter\UserProductQuery;
use App\Http\Action\Filter\WatchFilter;
use App\Http\Const\DefaultConst;
use App\Http\Resources\PerfumeProductResource;
use App\Http\Resources\PerfumeSearchResource;
use App\Models\Perfume;
use Illuminate\Http\Request;


class PerfumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PerfumeFilter $filter)
    {
        $result = $filter->queryRetriever($request->query())->sanitize()->arrayBuilder()->queryBuilder(Perfume::query()->with('images'));
        if (is_array($result)){
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }
        return PerfumeSearchResource::collection($result->appends($request->query()));
    }


    /**
     * Display the specified resource.
     */
    public function show(Perfume $perfume){
        return new PerfumeProductResource($perfume->load('images'));
    }
}
