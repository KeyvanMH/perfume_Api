<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Resources\DiscountAdminResource;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discount =  Discount::withTrashed()->orderBy('status')->paginate(DefaultConst::PAGINATION_NUMBER);
        return DiscountAdminResource::collection($discount);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'show';
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return 'desotry';
    }
}
