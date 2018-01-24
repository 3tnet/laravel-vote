<?php

namespace Ty666\LaravelVote\Traits;

use Illuminate\Database\Eloquent\Model;

trait CanBeVoted
{
    protected function voters()
    {
        $morphPrefix = config('vote.morph_prefix');
        return $this->morphToMany(config('vote.user'), $morphPrefix, config('vote.votes_table'))
            ->withPivot($morphPrefix . '_type', 'type', 'created_at')
            ->withTimestamps();
            //->using(Vote::class);
    }


    /**
     * Check if user is voted by given user.
     *
     * @param $user
     *
     * @return bool
     */
    protected function isVotedBy($user)
    {
        if ($user instanceof Model)
            $user = $user->getKey();
        return $this->voters()->where(config('vote.user_foreign_key'), $user)->exists();
    }

    public function downVoters()
    {
        return $this->voters()->wherePivot('type', 'down_vote');
    }

    /**
     * Check if user is down voted by given user.
     *
     * @param int $user
     *
     * @return bool
     */
    public function isDownVotedBy($user)
    {
        if ($user instanceof Model)
            $user = $user->getKey();
        return $this->downVoters()->where(config('vote.user_foreign_key'), $user)->exists();
    }

    public function upVoters()
    {
        return $this->voters()->wherePivot('type', 'up_vote');
    }

    /**
     * Check if user is up voted by given user.
     *
     * @param $user
     *
     * @return bool
     */
    public function isUpVotedBy($user)
    {
        if ($user instanceof Model)
            $user = $user->getKey();
        return $this->upVoters()->where(config('vote.user_foreign_key'), $user)->exists();
    }
}
