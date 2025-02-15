<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(){
        //todo resource file and pagination for it
        //todo cache
        return Province::all();
    }


    // get all cities of a province
    public function show(Province $province){
        //todo resource file and pagination for it
        //todo cache
        $province['cities'] = $province->cities;
        return $province;
    }



    public function update(Request $request,Province $province){
        $request->validate([
            'id' => 'required|integer|unique:provinces',
            'name' => 'required|string|max:255|min:2',
        ]);
        if(!$province->update($request->all())){
            return response()->json(['message' => DefaultConst::FAIL]);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
    public function destroy(Province $province){
        //todo observer for deleting corresponding originCity and DestinationCIty
        if(!$province->delete()){
            return response()->json(['message' => DefaultConst::FAIL]);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
}
