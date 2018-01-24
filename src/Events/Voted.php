<?php

namespace Ty666\LaravelVote\Events;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Ty666\LaravelVote\Exceptions\TargetException;


class Voted
{
    use SerializesModels;

    private $targetId;
    private $className;
    private $user;
    private $change;
    private $type;
    private $targetModel = null;

    /**
     * Voted constructor.
     * @param int $targetId
     * @param string $className
     * @param Model $user
     * @param array $change
     * @param string $type
     * @param Model|null $targetModel
     * @throws TargetException
     */
    public function __construct(int $targetId, string $className,Model $user, array $change, string $type, Model $targetModel = null)
    {
        $this->targetId = $targetId;
        $this->className = $className;
        $this->user = $user;
        $this->change = $change;
        $this->type = $type;
        if (!is_null($targetModel) && $targetModel->getKey() != $targetId){
            throw new TargetException('param targetModel key must equals param targetId');
        }

        $this->targetModel = $targetModel;
    }

    /**
     * @return int
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return Model
     */
    public function getUser(): Model
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getChange(): array
    {
        return $this->change;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Model
     */
    public function getTargetModel(): Model
    {
        if (is_null($this->targetModel)) {
            $this->targetModel = Container::getInstance()->make($this->className)->findOrFail($this->targetId);
        }
        return $this->targetModel;
    }

}
