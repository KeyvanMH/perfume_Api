<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryFullResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;



class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return CategoryResource::collection(Category::all());
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryFullResource($category);
    }

}
