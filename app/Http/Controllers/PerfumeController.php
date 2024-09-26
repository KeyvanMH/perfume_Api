<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfumeRequest;
use App\Http\Requests\UpdatePerfumeRequest;
use App\Http\Resources\PerfumeProductResource;
use App\Http\Resources\PerfumeSearchResource;
use App\Models\Perfume;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Action\UserQuery;

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
