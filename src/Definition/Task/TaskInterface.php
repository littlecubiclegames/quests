<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

interface TaskInterface
{
    /**
     * @param array<int, int> $progressMap
     */
    public function isFinished(array $progressMap): bool;

    /**
     * @return array<mixed>
     */
    public function getTaskIdTypes(): array;

    /**
     * @return array<mixed>
     */
    public function getTaskIdAttributes(): array;
}
