<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Http\Resources\AdminFaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AdminFaqResource::collection(Faq::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        //question , answer , is_active
        return Faq::create([
            'question' => $request->input('question'),
            'answer' => $request->input('answer'),
            'is_active' => $request->input('is_active')
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $id)
    {
        $faq = Faq::findOrFail('id',$id);
        $faq->question = $request->input('question');
        $faq->asnwer = $request->input('question');
        $faq->is_active = $request->input('is_active');
        $faq->save();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $id)
    {
        $faq = Faq::find($id);

        if ($faq) {
            $faq->delete();
        } else {
            return response()->json(['message' => 'نتیجه مورد نظر یافت نشد!'], 404);
        }
    }
}
