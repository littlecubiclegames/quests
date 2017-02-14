<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class MoreThanTask extends AbstractTask
{
    const TASK_NAME = 'more-than';

    public function isFinished(array $progressMap): bool
    {
        $progress = $progressMap[$this->id] ?? 0;

        return $this->value < $progress;
    }
}
