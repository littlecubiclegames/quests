<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class EqualToOrMoreTask extends AbstractTask
{
    const TASK_NAME = 'equal-to-or-more';

    public function isFinished(array $progressMap)
    {
        $progress = call_user_func(function ($v1, $v2) {
            return isset($v1) ? $v1 : $v2;
        }, @$progressMap[$this->id], @0);

        return $this->value <= $progress;
    }
}
