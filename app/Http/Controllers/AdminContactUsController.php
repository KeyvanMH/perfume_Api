<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminContactUsResource;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class AdminContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($status)
    {
        //TODO use cache
        switch ($status) {
            case 'all':
                return AdminContactUsResource::collection(ContactUs::withTrashed()->get());
                break;
            case 'activated':
                return AdminContactUsResource::collection(ContactUs::all());
                break;
            case 'deleted':
                return AdminContactUsResource::collection(ContactUs::onlyTrashed()->get());
                break;
            // Add more cases as needed
            default:
                // Handle unknown status
                return ['response' => 'موردی یافت نشد!'];
        }
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
