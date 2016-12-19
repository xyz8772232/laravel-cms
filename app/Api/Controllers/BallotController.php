<?php

namespace App\Api\Controllers;

use App\Api\Transformers\CommentTransformer;
use App\Ballot;
use App\BallotAnswer;
use App\BallotChoice;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
/**
 * Class BallotController
 *
 * @package \App\Api\Controllers
 */
class BallotController extends BaseController
{
    public function index()
    {
        $rules = ['article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->errorNotFound();
        }

        $comments = Comment::with('parent')->where('article_id', Input::get('article_id'))->paginate(20);
        return $this->paginator($comments, new CommentTransformer());

    }

    public function answer()
    {
        $rules = [
            'choice_ids.*' => 'required|integer|exists:ballot_choices,id',
            //'user_id' => 'required|integer|not_in:0',
        ];
        $choice_ids = Input::get('choice_ids', []);
        $user_id = Input::get('user_id', 0);
        $validator = Validator::make(['choice_ids' => $choice_ids, 'user_id' => $user_id], $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest('参数错误');
        }

        $ballot_id = BallotChoice::find($choice_ids[0])->ballot_id;

        if (!$ballot_id) {
            return $this->response->errorBadRequest('投票不存在或已过期');
        }

//        $answered = BallotAnswer::where('ballot_id', $ballot_id)->where('user_id', $user_id)->first();
//
//        if ($answered) {
//            return $this->response->errorBadRequest('已投过票');
//        }

        foreach ($choice_ids as $choice_id) {
            $result = BallotAnswer::create([
                'ballot_id' => $ballot_id,
                'choice_id' => $choice_id,
                'user_id' => $user_id,
            ]);
        }

        if ($result) {
            return $this->response->created();
        } else {
            return $this->response->errorInternal();
        }
    }

    public function result($ballot_id)
    {
        $choices = Ballot::with('choices')->find($ballot_id)->choices;
        //dd($choices);
        $total = $choices->sum('approve_num');
        $choices = $choices->map(function($choice) use($total) {
            return [
                'id' => $choice->id,
                'content' => $choice->content,
                'approve_num' => $choice->approve_num,
                'approve_percent' => empty($total) ? '0%' : intval(strval(round($choice->approve_num/$total, 2)*100)).'%',
            ];
        });
        return $this->response->array($choices->all());
    }
}
