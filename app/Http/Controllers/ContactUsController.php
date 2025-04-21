<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUsRequest $request)
    {
        ContactUs::Create([
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email') ?? null,
            'description' => $request->input('description'),
        ]);

        return response()->json(['response' => 'ok']);
    }
}
