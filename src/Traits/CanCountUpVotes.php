<?php

namespace Ty666\LaravelVote\Traits;


trait CanCountUpVotes
{
    // protected $upVotesCountField = 'up_votes_count';

    public function getUpVotesCount()
    {
        return $this->getAttribute($this->upVotesCountField);
    }

    public function incrementUpVotesCount($amount = 1)
    {
        return $this->increment($this->upVotesCountField, $amount);
    }

    public function decrementUpVotesCount($amount = 1)
    {
        return $this->decrement($this->upVotesCountField, $amount);
    }
}