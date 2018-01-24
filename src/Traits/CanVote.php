<?php

namespace Ty666\LaravelVote\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ty666\LaravelVote\Events\Voted;
use Ty666\LaravelVote\TargetFormatter;

trait CanVote
{

    private function isModelObject($targets)
    {
        return $targets instanceof Model || (is_array($targets) && $targets[0] instanceof Model) || ($targets instanceof Collection && $targets->first() instanceof Model);
    }

    protected function vote($type, $targets, $className = null)
    {
        $formattedTargets = TargetFormatter::format($targets, $className, ['type' => $type]);

        $isModelObject = $this->isModelObject($targets);
        if ($isModelObject) {
            if ($targets instanceof Model)
                $targets = collect([$targets]);
            else
                $targets = collect($targets);
            $keyName = $targets->first()->getKeyName();
        }

        foreach ($formattedTargets as $className => $targetIds) {

            $changes = $this->votings($className)->syncWithoutDetaching($targetIds);

            foreach ($changes['attached'] as $attached) {
                $voteChanges = [
                    'up_vote' => 0,
                    'down_vote' => 0,
                ];
                $voteChanges[$type] = 1;
                event(new Voted($attached, $className, $this, $voteChanges, $type, $isModelObject ? $targets->where($keyName, $attached)->first() : null));
            }

            foreach ($changes['updated'] as $updated) {
                $voteChanges = [
                    'up_vote' => -1,
                    'down_vote' => -1,
                ];
                $voteChanges[$type] = 1;
                event(new Voted($updated, $className, $this, $voteChanges, $type, $isModelObject ? $targets->where($keyName, $updated)->first() : null));
            }
        }

    }

    protected function votings($className)
    {
        $morphPrefix = config('vote.morph_prefix');
        return $this->morphedByMany($className, $morphPrefix, config('vote.votes_table'))
            ->withPivot($morphPrefix . '_type', 'type', 'created_at');
    }

    public function downVote($targets, $className = null)
    {
        $this->vote('down_vote', $targets, $className);
    }

    public function upVote($targets, $className = null)
    {
        $this->vote('up_vote', $targets, $className);
    }

    public function cancelVote($targets, $className = null)
    {
        $morphPrefix = config('vote.morph_prefix');
        $votableIdKey = "{$morphPrefix}_id";

        $formattedTargets = TargetFormatter::format($targets, $className);

        $isModelObject = $this->isModelObject($targets);
        if ($isModelObject) {
            if ($targets instanceof Model)
                $targets = collect([$targets]);
            else
                $targets = collect($targets);
            $keyName = $targets->first()->getKeyName();
        }

        foreach ($formattedTargets as $className => $targetIds) {

            $cancelVotes = $this->votings($className)->select(1)->find($targetIds);

            $cancelVoteIds = [];
            foreach ($cancelVotes as $cancelVote) {
                $changes = [
                    'up_vote' => 0,
                    'down_vote' => 0,
                ];
                $type = $cancelVote->pivot->type;
                $changes[$type] = -1;
                $votableId = $cancelVote->pivot->$votableIdKey;
                $cancelVoteIds[] = $votableId;
                event(new Voted($votableId, $className, $this, $changes, $type, $isModelObject ? $targets->where($keyName, $votableId)->first() : null));
            }
            $this->votings($className)->detach($cancelVoteIds);
        }
    }
}
