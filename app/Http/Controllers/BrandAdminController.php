<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\destroyBrandImageRequest;
use App\Http\Requests\StoreBrandImageRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandAdminResource;
use App\Http\Resources\BrandFullAdminResource;
use App\Models\Brand;
use App\Models\BrandImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BrandAdminResource::collection(Brand::withTrashed()->with('images')->paginate(DefaultConst::PAGINATION_NUMBER));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        DB::transaction(function () use ($request) {
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('/public/logo') : null;

            $brand = Brand::create([
                'name' => $request->validated('name')??'',
                'description' => $request->validated('description'),
                'title' => $request->validated('title'),
                'slug' => $request->validated('slug'),
                'logo' => $logoPath??null,
                'link' => $request->validated('link') ?? null,
            ]);

            if ($request->hasFile('images')) {
                $imagesData = collect($request->file('images'))->map(function ($image) use ($brand) {
                    if ($image === false) {
                        throw new \Exception('File upload failed');
                    }

                    return [
                        'image_path' => $image->store('/public/brandsImage'),
                        'alt' => $image->getClientOriginalName(),
                        'extension' => $image->extension(),
                        'size' => $image->getSize(),
                        'brand_id' => $brand->id,
                    ];
                });

                $brand->images()->createMany($imagesData);
            }

        });
            return response()->json(['response' => 'ok'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        // we wont use route model binding because we cant have deleted model with route model binding
        $result = Brand::withTrashed()->where('slug','=',$slug)->with('image')->get();
        return BrandFullAdminResource::collection($result);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        if($request->validated('logo')){
            if(Storage::exists('public/'.$brand->logo_path)){
                Storage::delete('public/'.$brand->logo_path);
            }
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('public/logo') : null;
            $brand->logo = $logoPath;
        }

        $fieldsToUpdate = ['slug', 'link', 'description', 'title'];
        foreach ($fieldsToUpdate as $field) {
            if ($request->filled($field)) {
                $brand->$field = $request->validated($field);
            }
        }
        $brand->save();
        return response()->json(['response' => 'ok'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //observer on Brand model for deleting related images
        $brand->delete();
        return response()->json(['response' => 'ok'],200);
    }

}
