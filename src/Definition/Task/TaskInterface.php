<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

interface TaskInterface
{
    public function isFinished(array $progressMap) : bool;

    public function getTaskIdTypes() : array;
}
