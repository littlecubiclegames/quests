<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

class NullTask implements TaskInterface
{
    public function isFinished(array $progressMap): bool
    {
        return true;
    }

    public function getTaskIdTypes(): array
    {
        return [];
    }

    public function getTaskIdAttributes(): array
    {
        return [];
    }
}
