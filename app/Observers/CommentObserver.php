<?php

namespace App\Observers;
use App\Comment;
use App\ArticleInfo;
/**
 * Class BallotAnswerObserver
 *
 * @package \App\Observers
 */
class CommentObserver
{
    public function created(Comment $comment)
    {
        $articleInfo = ArticleInfo::firstOrNew(['article_id' => $comment->article_id]);
        if ($articleInfo) {
            $articleInfo->comment_num++;
            $articleInfo->save();
        }
    }

    public function deleted(Comment $comment)
    {
        $articleInfo = ArticleInfo::firstOrNew(['article_id' => $comment->article_id]);
        if ($articleInfo && ($articleInfo->comment_num > 0)) {
            $articleInfo->comment_num--;
            $articleInfo->save();
        }
    }
}
