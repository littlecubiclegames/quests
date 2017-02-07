<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class OrTask implements TaskInterface
{
    /** @var TaskInterface[] */
    private $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function isFinished(array $progressMap): bool
    {
        foreach ($this->tasks as $task) {
            if ($task->isFinished($progressMap)) {
                return true;
            }
        }

        return false;
    }
}
