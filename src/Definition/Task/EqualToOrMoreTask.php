<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

class EqualToOrMoreTask extends AbstractTask
{
    const TASK_NAME = 'equal-to-or-more';

    public function isFinished(array $progressMap): bool
    {
        $progress = $progressMap[$this->id] ?? 0;

        return $this->value <= $progress;
    }
}
