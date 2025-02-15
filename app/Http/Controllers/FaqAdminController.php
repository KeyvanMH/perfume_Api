<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\AdminFaqResource;
use App\Models\Faq;



class FaqAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO use cache
        return AdminFaqResource::collection(Faq::withTrashed()->paginate(DefaultConst::PAGINATION_NUMBER));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        $user = Faq::create([
            'question' => $request->input('question'),
            'answer' => $request->input('answer'),
        ]);
        return AdminFaqResource::collection(collect([$user]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->question = $request->input('question');
        $faq->answer = $request->input('question');
        $faq->save();
        $faq->fill($request->only(['question', 'answer', 'is_active']))->save();
        return response()->json(['response' => 'ok'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['response' => 'ok'],200);
    }
}
