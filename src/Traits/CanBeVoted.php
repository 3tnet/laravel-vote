<?php

namespace Ty666\LaravelVote\Traits;

use Illuminate\Database\Eloquent\Model;
use DB;

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
    public function isVotedBy($user)
    {
        if ($user instanceof Model)
            $user = $user->getKey();
        return $this->voters()->where(config('vote.user_foreign_key'), $user)->count() > 0;
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
        return $this->downVoters()->wherePivot(config('vote.user_foreign_key'), $user)->count() > 0;
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
        return $this->upVoters()->wherePivot(config('vote.user_foreign_key'), $user)->count() > 0;
    }

    public function getVoteInfoByUser($user, $className = null)
    {
        if ($user instanceof Model) {
            $className = get_class($this);
            $user = $user->getKey();
        }
        $config = config('vote');
        return DB::table($config['votes_table'])->select('type', 'created_at')
            ->where($config['user_foreign_key'], $user)->where("{$config['morph_prefix']}_id", $this->getKey())
            ->where("{$config['morph_prefix']}_type", Relation::getMorphedModel($className) ?? $className)
            ->first();
    }
}
