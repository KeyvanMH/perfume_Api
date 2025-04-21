<?php

namespace App\Http\Controllers;

use App\Http\Action\Filter\PerfumeFilterService;
use App\Http\Const\DefaultConst;
use App\Http\Resources\PerfumeProductResource;
use App\Http\Resources\PerfumeSearchResource;
use App\Models\Perfume;
use App\Traits\ReserveProductManagement;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    use ReserveProductManagement;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PerfumeFilterService $filter)
    {
        $result = $filter->queryRetriever($request->query())->sanitize()->eloquentQueryBuilder()->get(Perfume::query()->with('images'));
        if (is_array($result)) {
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }

        return PerfumeSearchResource::collection($result->appends($request->query()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfume $perfume)
    {
        $perfume->quantity = $perfume->quantity - $this->getReservedProduct($perfume->id, 'perfume');

        return new PerfumeProductResource($perfume->load('images'));
    }
}
