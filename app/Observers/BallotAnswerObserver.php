<?php

namespace App\Observers;
use App\BallotAnswer;
use App\BallotChoice;

/**
 * Class BallotAnswerObserver
 *
 * @package \App\Observers
 */
class BallotAnswerObserver
{
    public function created(BallotAnswer $ballotAnswer)
    {
        $ballotChoice = BallotChoice::find($ballotAnswer->choice_id);
        if ($ballotChoice) {
            $ballotChoice->approve_num++;
            $ballotChoice->save();
        }
    }

    public function deleted(BallotAnswer $ballotAnswer)
    {
        $ballotChoice = BallotChoice::find($ballotAnswer->choice_id);
        if ($ballotChoice) {
            $ballotChoice->approve_num--;
            $ballotChoice->save();
        }
    }
}
