<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        //TODO use cache
        return FaqResource::collection(Faq::all());
    }
}
