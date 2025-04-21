<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Perfume;
use App\Models\PerfumeComment;
use App\Models\PerfumeCommentReply;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    // todo make document

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Perfume $perfume)
    {
        // todo limiation on comment
        // store comment

        if (! $perfume->comments()->create([
            'user_id' => auth()->user()->id,
            'comment' => $request->validated('comment'),
        ])) {
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comments = PerfumeComment::with(['user'])->where('perfume_id', $id)->paginate(DefaultConst::PAGINATION_NUMBER);
        // relation between 3 table ( cant use eloquent function for eager loading)
        foreach ($comments as $comment) {
            $comment->replies = PerfumeCommentReply::with('user')->where('perfume_comment_id', '=', $comment->id)->get();
        }

        return CommentResource::collection($comments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerfumeComment $perfumeComment)
    {
        // soft delete comment and its replies
        // todo only delete if he owns the comment

        if (Gate::denies('manipulate-comment', $perfumeComment)) {
            return response()->json(['message' => DefaultConst::UNAUTHORIZE], 403);
        }
        $perfumeComment->replies()->delete();
        $perfumeComment->delete();

        return response()->json(['message' => DefaultConst::SUCCESSFUL], 200);
    }
}
