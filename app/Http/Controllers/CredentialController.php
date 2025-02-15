<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\UpdateCredentialRequest;
use App\Http\Resources\CredentialResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show()
    {
        return new CredentialResource(auth()->user());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCredentialRequest $request)
    {
        if (!$request->validated()){
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }
        if(!$this->updateCredentials($request->validated())){
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    private function updateCredentials(array $request){
        try {
            return (bool)auth()->user()->update($request);
        }catch(\Exception $e){
            return $e;
        }
    }
    public static function isCredentialsComplete():bool{
        return !(!auth()->user()->phone_number
            || !auth()->user()->first_name
            || !auth()->user()->last_name
            || !auth()->user()->post_number
            || !auth()->user()->city_id
        );
    }
    public static function hasEmail():bool {
        return (bool)auth()->user()->email;
    }
}
