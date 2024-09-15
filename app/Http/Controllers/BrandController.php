<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandFullResource;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        //TODO use pagination
        return BrandResource::collection(Brand::with('image')->get());
    }



    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new BrandFullResource($brand->load('image'));
    }


}
