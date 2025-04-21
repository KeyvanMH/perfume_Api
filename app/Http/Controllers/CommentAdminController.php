<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Resources\CommentAdminResource;
use App\Models\PerfumeComment;

class CommentAdminController extends Controller
{
    // todo make document

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // show comment with trashed
        $comments = PerfumeComment::where('perfume_id', '=', $id)->with(['user', 'replies'])->withTrashed()->paginate(DefaultConst::PAGINATION_NUMBER);

        return CommentAdminResource::collection($comments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerfumeComment $perfumeComment)
    {
        if (! $perfumeComment->replies()->delete() || ! $perfumeComment->delete()) {
            return response()->json(['message' => DefaultConst::FAIL]);
        }

        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
}
