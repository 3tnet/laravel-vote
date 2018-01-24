<?php

namespace Ty666\LaravelVote\Contracts;

interface CanCountUpVotesModel
{
    public function getUpVotesCount();

    public function incrementUpVotesCount($amount = 1);

    public function decrementUpVotesCount($amount = 1);
}
