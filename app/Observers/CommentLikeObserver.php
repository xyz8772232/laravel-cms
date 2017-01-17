<?php

namespace App\Observers;
use App\CommentLike;
use App\Comment;
/**
 * Class BallotAnswerObserver
 *
 * @package \App\Observers
 */
class CommentLikeObserver
{
    public function created(CommentLike $commentLike)
    {
        $comment = Comment::find($commentLike->comment_id);
        if ($comment) {
            $comment->like_num++;
            $comment->save();
        }
    }

    public function deleted(CommentLike $commentLike)
    {
        $comment = Comment::find($commentLike->comment_id);
        if ($comment && ($comment->like_num > 0)) {
            $comment->like_num--;
            $comment->save();
        }
    }
}
