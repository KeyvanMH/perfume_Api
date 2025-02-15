<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreBrandImageRequest;
use App\Models\Brand;
use App\Models\BrandImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BrandImageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandImageRequest $request , Brand $brand) {
        if(!$request->hasFile('images') || !$request->validated('images')) {
            return response()->json(['message' => DefaultConst::NOT_FOUND], 404);
        }
            DB::transaction(function () use ($request, $brand) {
                $imagesData = collect($request->file('images'))->map(function ($image) use ($brand) {
                    if ($image === false) {
                        throw new \Exception('File upload failed');
                    }
                    return [
                        'image_path' => $image->store('public/brandsImage'),
                        'alt' => $image->getClientOriginalName(),
                        'extension' => $image->extension(),
                        'size' => $image->getSize(),
                        'brand_id' => $brand->id,
                    ];
                });

                $brand->images()->createMany($imagesData);
            });
            return response()->json(['response' => 'ok'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(BrandImage $brandImage)
    {
        // Construct the path to the image
        $path = $brandImage->image_path;
        // Check if the image exists
        if (!Storage::exists($path)) {
            return response()->json(['message' => DefaultConst::NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        $imageContent = Storage::get($path);
        $mimeType = Storage::mimeType($path);
        return response($imageContent, Response::HTTP_OK)
            ->header('Content-Type', $mimeType);
    }


    /**
     * remove specific Image
     */
    public function destroy(BrandImage $brandImage) {
        //check the input for the image to exist in DB , delete the image and index in table, return response
        if(Storage::exists($brandImage->image_path)){
            Storage::delete($brandImage->image_path);
        }
        $brandImage->delete();
        return response()->json(['response' => 'ok'],200);
    }

    public function showLogo(Brand $brand){
        $path = $brand->logo;
        if(!$path){
            return response()->json(['message' => DefaultConst::NOT_FOUND]);
        }
        // Check if the image exists
        if (!Storage::exists($path)) {
            return response()->json(['message' => DefaultConst::NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        $imageContent = Storage::get($path);
        $mimeType = Storage::mimeType($path);
        return response($imageContent, Response::HTTP_OK)
            ->header('Content-Type', $mimeType);
    }

    public function destroyAllImage(Brand $brand) {
        $images = $brand->images;
        foreach ($images as $image) {
            if (Storage::exists($image->image_path)) {
                Storage::delete($image->image_path);
            }
            $image->delete();
        }
        return response()->json(['response' => 'ok'], 200);
    }

}
