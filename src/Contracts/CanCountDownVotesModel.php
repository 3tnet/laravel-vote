<?php

namespace Ty666\LaravelVote\Contracts;

interface CanCountDownVotesModel
{
    public function getDownVotesCount();

    public function incrementDownVotesCount($amount = 1);

    public function decrementDownVotesCount($amount = 1);
}
