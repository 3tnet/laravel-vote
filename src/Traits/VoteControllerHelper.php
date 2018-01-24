<?php

namespace Ty666\LaravelVote\Traits;


use Ty666\CdnPusher\Contracts\CanCountUpVoteModel;

trait VoteControllerHelper
{
    protected $resourceClass;

    protected function getResourceClass()
    {
        if (empty($this->resourceClass)) {
            $this->resourceClass = str_singular(rtrim(class_basename(__CLASS__), 'Controller'));
        }
        return $this->resourceClass;
    }

    public function upVote($id)
    {
        $model = app($this->getResourceClass())->findOrFail($id);
        $change = auth()->user()->upVote($model);
        return $this->voteResponse($model, $change);
    }

    public function downVote($id)
    {
        $model = app($this->getResourceClass())->findOrFail($id);
        $change = auth()->user()->downVote($model);
        return $this->voteResponse($model, $change);

    }

    public function cancelVote($id)
    {
        $model = app($this->getResourceClass())->findOrFail($id);
        $change = auth()->user()->cancelVote($model);
        return $this->voteResponse($model, $change);
    }

    protected function voteResponse($model, $change)
    {
        $response = [
            'up_vote_change' => $change['up_vote']
        ];
        if ($model instanceof CanCountUpVoteModel)
            $response['up_votes'] = $model->getUpVotesCount();
        return response()->json($response);
    }
}
