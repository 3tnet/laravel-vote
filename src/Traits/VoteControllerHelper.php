<?php

namespace Ty666\LaravelVote\Traits;


use Symfony\Component\HttpFoundation\Response;
use Ty666\LaravelVote\Contracts\CanCountUpVotesModel;
use Exception;

trait VoteControllerHelper
{
    // protected $resourceClass;

    protected function getResourceClass()
    {
        if (!property_exists($this, 'resourceClass'))
            throw new Exception('VoteController must has resourceClass property, please set resourceClass in your VoteController.');
        return $this->resourceClass;
    }

    public function upVote($id)
    {
        $model = app($this->getResourceClass())->findOrFail($id);
        auth()->user()->upVote($model);
        return $this->voteResponse($model);
    }

    public function downVote($id)
    {
        $model = app($this->getResourceClass())->findOrFail($id);
        auth()->user()->downVote($model);
        return $this->voteResponse($model);

    }

    public function cancelVote($id)
    {
        $model = app($this->getResourceClass())->findOrFail($id);
        auth()->user()->cancelVote($model);
        return $this->voteResponse($model);
    }

    protected function voteResponse($model)
    {
        if ($model instanceof CanCountUpVotesModel)
            return response()->json(['up_votes_count' => $model->getUpVotesCount()]);
        else
            return response(null, Response::HTTP_NO_CONTENT);
    }
}
