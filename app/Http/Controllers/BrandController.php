<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Resources\BrandFullResource;
use App\Http\Resources\BrandResource;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return BrandResource::collection(Brand::with('images')->paginate(DefaultConst::PAGINATION_NUMBER));
    }



    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new BrandFullResource($brand->load('images'));
    }

}
