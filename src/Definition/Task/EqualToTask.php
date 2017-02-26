<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

class EqualToTask extends AbstractTask
{
    const TASK_NAME = 'equal-to';

    public function isFinished(array $progressMap): bool
    {
        $progress = $progressMap[$this->id] ?? 0;

        return $this->value === $progress;
    }
}
