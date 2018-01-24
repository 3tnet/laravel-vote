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
        if ($this->getUpVotesCount() >= $amount)
            return $this->decrement($this->upVotesCountField, $amount);
        else{
            $this->setAttribute($this->upVotesCountField, 0);
            return $this->save();
        }

    }
}