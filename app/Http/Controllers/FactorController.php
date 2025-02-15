<?php

namespace App\Http\Controllers;

use App\Http\Action\Image\Base64Image;
use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreFactorRequest;
use App\Http\Requests\StorePerfumeBasedFactorRequest;
use App\Http\Requests\UpdateFactorRequest;
use App\Http\Requests\UpdatePerfumeBasedFactorRequest;
use App\Http\Resources\FactorResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Factor;
use App\Models\Perfume;
use App\Models\PerfumeBasedFactor;
use App\Models\PerfumeImage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\throwException;


class FactorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FactorResource::collection(Factor::withTrashed()->with(['perfumeBasedFactor','user'])->paginate(DefaultConst::PAGINATION_NUMBER));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfumeBasedFactorRequest $request)
    {
        //todo change document and discount_percent
        //todo if the catogory or brand doesnt exist , do we still create it???
        $products = $request->validated('products');
        DB::beginTransaction();
        $factor = Factor::create([
            'user_id' => $request->user()->id,
        ]);
        foreach ($products as $product){
            $categoryId = $this->categoryId($product['category']);
            $brandId = $this->brandId($product['brand']);
            // Check if the perfume exists
            $perfume = Perfume::where('slug', '=', $product['slug'])->first();
            if (is_null($perfume)) {
               $perfume = $this->createPerfume($product,$brandId,$categoryId);
            } else {
                //TODO think about how to manage discount cards and discounts
                $this->updatePerfume($product,$perfume,$brandId,$categoryId);
            }
            $this->createPerfumeBasedFactor($product,$factor,$perfume);
            if(isset($product['images'])){
                $this->storeImage($perfume,$product);
            }
        }
        DB::commit();
        return response()->json(['response' => DefaultConst::SUCCESSFUL],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $factor =  Factor::withTrashed()->with(['perfumeBasedFactor'])->where('id','=',$id)->firstOrFail();
        return new FactorResource($factor);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factor $factor)
    {
        if(!Gate::allows('manipulate_factor',$factor)){
            return response()->json(['response' => 'unauthenticated']);
        }
        // delete perfumeBasedFactor and update Perfume::class stock via FactorObserver
        $factor->delete();
        return response()->json(['response' => DefaultConst::SUCCESSFUL]);
    }

    public function indexAdminFactor(User $user){
        if($user->role == 'product_admin' or $user->role == 'super_admin'){
            $factors = $user->factors()->with('perfumeBasedFactor')->paginate(DefaultConst::PAGINATION_NUMBER);
            return FactorResource::collection($factors);
        }
        return response()->json(['response' => 'یوزر درخواستی ادمین نمیباشد'],404);
    }

    private function categoryId($productCategory) {
        $category = Category::where('slug', '=', $productCategory)->first();
        return $category->id;
    }
    private function brandId($productBrand){
        $brand = Brand::where('slug', '=', $productBrand)->first();
        return $brand->id;
    }
    private function createPerfume($product,$brandId,$categoryId){
        return Perfume::create([
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $product['quantity'],
            'volume' => $product['volume'],
            'description' => $product['description'],
            'slug' => $product['slug'],
            'warranty' => $product['warranty']??null,
            'gender' => $product['gender'],
            'discount_percent' => $product['percent'] ?? null,
            'discount_amount' => $product['amount'] ?? null,
            'discount_start_date' => $product['start_date']?? null,
            'discount_end_date' => $product['end_date']?? null,
            'discount_card' => $product['discount_card']?? null,
            'discount_card_percent' => $product['discount_card_percent']?? null,
        ]);
    }

    private function createPerfumeBasedFactor($product,$factor,$perfume) {
        PerfumeBasedFactor::create([
            'factor_id' => $factor->id,
            'perfume_id' => $perfume->id,
            'name' => $product['name'],
            'price' => $product['price'],
            'volume' => $product['volume'],
            'stock' => $product['quantity'],
            'gender' => $product['gender'],
            'warranty' => $product['warranty'] ?? null,
        ]);
    }

    private function updatePerfume($product,$perfume,$brandId,$categoryId) {
        $perfume->category_id = $categoryId;
        $perfume->brand_id = $brandId;
        $perfume->price = $product['price'];
        $perfume->quantity += $product['quantity'];
        $perfume->save();
    }

    private function storeImage(mixed $perfume, mixed $product) {
        $imageContent = collect($product['images'])->map(function($base64String) {
            $imageObj = new Base64Image($base64String);
            return [
                'image_path' => $imageObj->save('public/perfumeImage/'),
                'alt' => $imageObj->name,
                'extension' => $imageObj->mimeType,
                'size' => $imageObj->size,
            ];
        });
        if(!$perfume->images()->createMany($imageContent)){
            throw new Exception('cant save  image in DB');
        }
    }

}
