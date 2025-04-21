<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Perfume;
use App\Models\PerfumeComment;
use App\Models\PerfumeCommentReply;
use Illuminate\Support\Facades\Gate;

class CommentReplyController extends Controller
{
    // todo make document
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, PerfumeComment $perfumeComment)
    {
        // todo limitation on limit
        if (! $perfumeComment->replies()->create([
            'user_id' => auth()->user()->id,
            // 'perfume_comment_id' => $perfumeComment->id,
            'reply' => $request->validated('comment'),
        ])) {
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerfumeCommentReply $perfumeReply)
    {
        if (Gate::denies('manipulate-reply', $perfumeReply)) {
            return response()->json(['message' => DefaultConst::UNAUTHORIZE], 403);
        }
        if (! $perfumeReply->delete()) {
            return response()->json(['message' => DefaultConst::FAIL]);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
}
