<?php

namespace Ty666\LaravelVote\Traits;


trait CanCountDownVotes
{
    // protected $downVotesCountField = 'down_votes_count';

    public function getDownVotesCount()
    {
        return $this->getAttribute($this->downVotesCountField);
    }

    public function incrementDownVotesCount($amount = 1)
    {
        return $this->increment($this->downVotesCountField, $amount);
    }

    public function decrementDownVotesCount($amount = 1)
    {
        if ($this->getDownVotesCount() >= $amount)
            return $this->decrement($this->downVotesCountField, $amount);
        else {
            $this->setAttribute($this->downVotesCountField, 0);
            return $this->save();
        }
    }

}