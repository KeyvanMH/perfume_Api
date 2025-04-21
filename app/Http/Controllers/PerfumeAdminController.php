<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StorePerfumeRequest;
use App\Http\Requests\UpdatePerfumeRequest;
use App\Http\Resources\PerfumeOfPerfumeBasedFactor;
use App\Http\Resources\PerfumeProductAdminResource;
use App\Http\Resources\PerfumeSearchAdminResource;
use App\Http\Services\Filter\PerfumeFilterService;
use App\Models\Perfume;
use App\Traits\ReserveProductManagement;
use Illuminate\Http\Request;

class PerfumeAdminController extends Controller
{
    use ReserveProductManagement;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PerfumeFilterService $filter)
    {
        $query = Perfume::query();
        $result = $filter->queryRetriever($request->query())->sanitize()->eloquentQueryBuilder()->get($query->withTrashed());
        if (is_array($result)) {
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }

        return PerfumeSearchAdminResource::collection($result->appends($request->query()));
    }

    /**
     * Display a listing of the FactorPerfumes created the selling perfume.
     */
    public function indexBasedFactor($slug)
    {
        $perfume = Perfume::withTrashed()->where('slug', '=', $slug)->with('perfumeBasedFactor')->paginate(DefaultConst::PAGINATION_NUMBER);

        return PerfumeOfPerfumeBasedFactor::collection($perfume);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeRequest $request)
    {
        // todo we shouldnt be able to add product without factor
        $perfume = Perfume::create([
            'name' => $request->validated('name'),
            'price' => $request->validated('price'),
            'volume' => $request->validated('volume'),
            'quantity' => $request->validated('quantity'),
            'description' => $request->validated('description'),
            'slug' => $request->validated('slug'),
            'warranty' => $request->validated('warranty'),
            'gender' => $request->validated('gender'),
            'percent' => $request->validated('percent') ?? null,
            'amount' => $request->validated('amount') ?? null,
            'start_date' => $request->validated('start_date'),
            'end_date' => $request->validated('end_date'),
            'discount_card' => $request->validated('discount_card'),
        ]);

        return response()->json(['response' => 'ok'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $perfume = Perfume::withTrashed()->where('slug', '=', $slug)->get();
        $perfume->reserved = $perfume->quantity - $this->getReservedProduct($perfume->id, 'perfume');

        return PerfumeProductAdminResource::collection($perfume);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerfumeRequest $request, Perfume $perfume)
    {
        // TODO change category and brand
        return $perfume->updateOrFail($request->validated());
        // TODO change to resposne after checking if it works
        //        return response()->json(['response' => 'ok']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfume $perfume)
    {
        // TODO check if is it possible to delete data because of the sold table restrict
        $perfume->delete();

        return response()->json(['response' => 'ok']);
    }
}
