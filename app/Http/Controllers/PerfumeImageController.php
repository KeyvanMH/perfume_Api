<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StorePerfumeImageRequest;
use App\Models\Perfume;
use App\Models\PerfumeImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PerfumeImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeImageRequest $request, Perfume $perfume)
    {
        if (! $request->hasFile('images') || ! $request->validated('images')) {
            return response()->json(['message' => DefaultConst::NOT_FOUND], 404);
        }
        DB::transaction(function () use ($request, $perfume) {
            $perfume->images()->createMany(collect($request->file('images'))->map(function ($image) {
                if ($image === false) {
                    throw new \Exception('File upload failed');
                }

                return [
                    'image_path' => $image->store('public/perfumeImage'),
                    'alt' => $image->getClientOriginalName(),
                    'extension' => $image->extension(),
                    'size' => $image->getSize(),
                ];
            }));
        });

        return response()->json(['message' => DefaultConst::SUCCESSFUL], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(PerfumeImage $perfumeImage)
    {
        return response()->file(storage_path('app/'.$perfumeImage->image_path));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerfumeImage $perfumeImage)
    {
        if (Storage::exists($perfumeImage->image_path)) {
            Storage::delete($perfumeImage->image_path);
        }
        if (! $perfumeImage->delete()) {
            return response()->json(['message' => DefaultConst::FAIL], 500);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL], 200);
    }

    public function destroyAllImage(Perfume $perfume)
    {
        $images = $perfume->images;
        foreach ($images as $image) {
            if (Storage::exists($image->image_path)) {
                Storage::delete($image->image_path);
            }
            $image->delete();
        }

        return response()->json(['response' => 'ok'], 200);

    }
}
