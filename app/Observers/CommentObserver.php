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
        if (empty($comment->reply_to_id)) {
            $articleInfo = ArticleInfo::firstOrNew(['article_id' => $comment->article_id]);
            if ($articleInfo) {
                $articleInfo->comment_num++;
                $articleInfo->save();
            }
        }
    }

    public function deleted(Comment $comment)
    {
        if (empty($comment->reply_to_id)) {
            $articleInfo = ArticleInfo::firstOrNew(['article_id' => $comment->article_id]);
            if ($articleInfo && ($articleInfo->comment_num > 0)) {
                $articleInfo->comment_num--;
                $articleInfo->save();
            }
        }
    }

    public function updated(Comment $comment)
    {
        if (empty($comment->reply_to_id)) {
            if ($comment->blocked == 1 && $comment->getOriginal('blocked') == 0) {
                $articleInfo = ArticleInfo::firstOrNew(['article_id' => $comment->article_id]);
                if ($articleInfo && ($articleInfo->comment_num > 0)) {
                    $articleInfo->comment_num--;
                    $articleInfo->save();
                }
            } elseif ($comment->blocked == 0 && $comment->getOriginal('blocked') == 1) {
                $articleInfo = ArticleInfo::firstOrNew(['article_id' => $comment->article_id]);
                if ($articleInfo) {
                    $articleInfo->comment_num++;
                    $articleInfo->save();
                }
            }
        }
    }
}
