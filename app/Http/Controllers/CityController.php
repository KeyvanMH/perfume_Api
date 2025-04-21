<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index()
    {
        // todo resource file and pagination for it
        // todo cache
        return City::all();
    }

    public function store(Request $request)
    {
        // store both province and its cities here , we dont have store in ProvinceController
        DB::beginTransaction();
        foreach ($request->all() as $province) {
            $provinceCreated = Province::create([
                'id' => $province['id'],
                'name' => $province['name'],
            ]);
            $cities = collect($province['cities'])->map(function ($city) {
                return [
                    'id' => $city['id'],
                    'name' => $city['name'],
                ];
            });
            $provinceCreated->cities()->createMany($cities);
        }
        DB::commit();

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'id' => 'required|integer|unique:origin_cities',
            'name' => 'required|string|max:255|min:3',
        ]);
        if (! $city->update($request->all())) {
            return response()->json(['message' => DefaultConst::FAIL]);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    public function destroy(City $city)
    {
        // todo observer for deleting corresponding user to make it null
        if (! $city->delete()) {
            return response()->json(['message' => DefaultConst::FAIL]);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
}
