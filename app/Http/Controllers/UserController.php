<?php

namespace App\Http\Controllers;

use App\Http\Action\Filter\UserReport;
use App\Http\Const\DefaultConst;
use App\Http\Resources\UserForAdminResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,UserReport $userReport)
    {
        //todo important doc needed
        return UserForAdminResource::collection(
            $userReport->urlQueryRetriever($request->query())->get(User::query())
        );
    }
    public function store(Request $request){
        return 'store';
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {
        return new UserForAdminResource(User::withTrashed()->where('id','=',$userId)->firstOrFail());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(!$user->delete()){
            return response()->json(['message' => DefaultConst::FAIL]);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
}
