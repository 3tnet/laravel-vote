<?php

namespace Ty666\LaravelVote;

use Illuminate\Database\Eloquent\Model;
use Ty666\LaravelVote\Exceptions\TargetException;


class TargetFormatter
{
    public static function format($targets, $className = null, array $update = [])
    {
        $formattedTargets = [];
        if (is_null($targets)) {
            throw new TargetException('target can not be null.');
        }
        if (!is_iterable($targets))
            $targets = [$targets];
        foreach ($targets as $target) {
            if (is_numeric($target) && is_null($className))
                throw new TargetException('class name can not be null when target is numeric.');
            if ($target instanceof Model) {
                $targetClassName = get_class($target);
                $target = $target->getKey();
            } else {
                $targetClassName = $className;
            }
            if (empty($update))
                $formattedTargets[$targetClassName][] = intval($target);
            else
                $formattedTargets[$targetClassName][intval($target)] = $update;
        }
        return $formattedTargets;
    }
}
