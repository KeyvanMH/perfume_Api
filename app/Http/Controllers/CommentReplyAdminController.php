<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Models\PerfumeCommentReply;
use Illuminate\Http\Request;

class CommentReplyAdminController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerfumeCommentReply $perfumeCommentReply)
    {
        if(!$perfumeCommentReply->delete()){
            return response()->json(['message' => DefaultConst::FAIL]);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }
}
