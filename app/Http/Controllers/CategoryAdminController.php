<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryAdminResource;
use App\Http\Resources\CategoryFullAdminResource;
use App\Models\Category;


class CategoryAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return CategoryAdminResource::collection(Category::withTrashed()->paginate(DefaultConst::PAGINATION_NUMBER));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create([
            'name' => $request->validated('name'),
            'type' => $request->validated('type'),
            'description' => $request->validated('description'),
            'slug' => $request->validated('slug')
        ]);
        return response()->json(['responses' => 'ok'],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $result = Category::withTrashed()->where('slug','=',$slug)->get();
        return  CategoryFullAdminResource::collection($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category , UpdateCategoryRequest $request)
    {
        $fieldsToUpdate = ['name', 'type', 'description', 'slug'];
        foreach ($fieldsToUpdate as $field) {
            if ($request->filled($field)) {
                $category->$field = $request->validated($field);
            }
        }
        $category->save();
        return response()->json(['responses' => 'ok'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['responses' => 'ok'],200);
    }
}
