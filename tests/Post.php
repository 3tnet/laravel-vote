<?php

namespace Ty666\LaravelVote\Tests;

use Illuminate\Database\Eloquent\Model;
use Ty666\LaravelVote\Contracts\CanCountUpVotesModel;
use Ty666\LaravelVote\Traits\CanBeVoted;
use Ty666\LaravelVote\Traits\CanCountUpVotes;

class Post extends Model implements CanCountUpVotesModel
{
    use CanBeVoted, CanCountUpVotes;

    protected $table = 'posts';
    protected $fillable = ['name'];

    protected $upVotesCountField = 'up_votes_count';
}
