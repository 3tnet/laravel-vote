<?php

namespace Ty666\LaravelVote\Contracts;

interface VoteController
{
    public function upVote($id);

    public function downVote($id);

    public function cancelVote($id);
}
