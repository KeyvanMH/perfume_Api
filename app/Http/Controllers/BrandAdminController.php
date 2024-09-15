<?php

namespace App\Http\Controllers;

use App\Http\Requests\destroyBrandImageRequest;
use App\Http\Requests\StoreBrandImageRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandFullAdminResource;
use App\Http\Resources\BrandFullResource;
use App\Models\Brand;
use App\Models\BrandImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class BrandAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BrandFullAdminResource::collection(Brand::withTrashed()->with('image')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        DB::transaction(function () use ($request) {
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logo', 'public') : null;

            $brand = Brand::create([
                'description' => $request->input('description'),
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'logo_path' => $logoPath,
                'link' => $request->input('link') ?? null,
            ]);

            if ($request->hasFile('images')) {
                $imagesData = collect($request->file('images'))->map(function ($image) use ($brand) {
                    return [
                        'image_path' => $image->store('brandsImage', 'public'),
                        'alt' => $image->getClientOriginalName(),
                        'extension' => $image->extension(),
                        'size' => $image->getSize(),
                        'brand_id' => $brand->id,
                    ];
                });

                $brand->image()->createMany($imagesData);
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
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logo', 'public') : null;
            $brand->logo_path = $logoPath;
        }

        $fieldsToUpdate = ['slug', 'link', 'description', 'title'];
        foreach ($fieldsToUpdate as $field) {
            if ($request->filled($field)) {
                $brand->$field = $request->validated($field);
            }
        }
        $brand->save();
        return ['response' => 'ok'];
    }
    /**
     * add Images for specific brand
     */
    public function storeImage(StoreBrandImageRequest $request , Brand $brand) {
        if($request->validated('images')) {
            DB::transaction(function () use ($request, $brand) {
                $imagesData = collect($request->file('images'))->map(function ($image) use ($brand) {
                    return [
                        'image_path' => $image->store('brandsImage', 'public'),
                        'alt' => $image->getClientOriginalName(),
                        'extension' => $image->extension(),
                        'size' => $image->getSize(),
                        'brand_id' => $brand->id,
                    ];
                });

                $brand->image()->createMany($imagesData);
            });
            return ['response' => 'ok'];
        }else{
            return ['response' => 'عکسی یافت نشد'];
        }

    }
    /**
     * remove specific Image
     */
    public function destroyImage(destroyBrandImageRequest $request) {
        //check the input for the image to exist in DB , delete the image and index in table, return response
        if(Storage::exists('public/'.$request->validated('imageName'))){
            Storage::delete('public/'.$request->validated('imageName'));
        }

        $brandImage = BrandImage::where('image_path','=',$request->validated('imageName'))->first();
        if (!$brandImage){
            return ['response' => 'موردی یافت نشد'];
        }
        $deleted = $brandImage->delete();
        return ['response' => $deleted,'message' => 'عکس با موفقیت حذف شد'];
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return ['response' => 'ok'];
    }
}
