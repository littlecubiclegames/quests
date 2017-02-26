<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

class OrTask implements TaskInterface
{
    const TASK_NAME = 'or';

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

    public function getTaskIdTypes(): array
    {
        return array_reduce($this->tasks, function ($map, TaskInterface $task) {
            return $map + $task->getTaskIdTypes();
        }, []);
    }
}
