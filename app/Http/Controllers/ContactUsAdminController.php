<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Resources\AdminContactUsResource;
use App\Models\ContactUs;


class ContactUsAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($status)
    {
        //TODO use cache
        return AdminContactUsResource::collection(ContactUs::withTrashed()->paginate(DefaultConst::PAGINATION_NUMBER));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactUs $contactUs)
    {
        $contactUs->delete();
        return response()->json(['response' => 'ok'],200);
    }
}
