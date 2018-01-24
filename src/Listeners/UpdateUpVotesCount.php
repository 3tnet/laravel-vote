<?php

namespace Ty666\LaravelVote\Listeners;


use Ty666\LaravelVote\Contracts\CanCountUpVotesModel;
use Ty666\LaravelVote\Events\Voted;

class UpdateUpVotesCount
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

        if ($event->getTargetModel() instanceof CanCountUpVotesModel) {
            $upVoteChange = $event->getChange()['up_vote'];
            if ($upVoteChange > 0) {

                $event->getTargetModel()->incrementUpVotesCount($upVoteChange);
            } elseif ($upVoteChange < 0) {
                $event->getTargetModel()->decrementUpVotesCount(abs($upVoteChange));
            }
        }
    }
}
