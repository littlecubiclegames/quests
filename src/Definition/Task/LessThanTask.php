<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class LessThanTask extends AbstractTask
{
    const TASK_NAME = 'less-than';

    public function isFinished(array $progressMap): bool
    {
        $progress = $progressMap[$this->id] ?? 0;

        return $this->value > $progress;
    }
}
