<?php

namespace Ty666\LaravelVote\Tests;

use Illuminate\Database\Eloquent\Model;
use Ty666\LaravelVote\Contracts\CanCountDownVotesModel;
use Ty666\LaravelVote\Contracts\CanCountUpVotesModel;
use Ty666\LaravelVote\Traits\CanBeVoted;
use Ty666\LaravelVote\Traits\CanCountDownVotes;
use Ty666\LaravelVote\Traits\CanCountUpVotes;

class Other extends Model implements CanCountUpVotesModel, CanCountDownVotesModel
{
    use CanBeVoted, CanCountUpVotes, CanCountDownVotes;
    protected $table = 'others';
    protected $fillable = ['name'];

    protected $upVotesCountField = 'up_votes_count';

    protected $downVotesCountField = 'down_votes_count';
}
