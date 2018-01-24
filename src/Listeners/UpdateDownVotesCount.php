<?php

namespace Ty666\LaravelVote\Listeners;


use Ty666\LaravelVote\Contracts\CanCountDownVotesModel;
use Ty666\LaravelVote\Events\Voted;

class UpdateDownVotesCount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Voted $event)
    {
        if ($event->getTargetModel() instanceof CanCountDownVotesModel) {
            $downVoteChange = $event->getChange()['down_vote'];
            if ($downVoteChange > 0) {
                $event->getTargetModel()->incrementDownVotesCount($downVoteChange);
            } elseif ($downVoteChange < 0) {
                $event->getTargetModel()->decrementDownVotesCount(abs($downVoteChange));
            }
        }
    }
}
