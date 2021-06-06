<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

class TaskBuilder
{
    public function build(array $taskData): TaskInterface
    {
        $children = [];
        if (isset($taskData['children'])) {
            $children = array_map(function (array $taskData): TaskInterface {
                return $this->build($taskData);
            }, $taskData['children']);
        }

        switch ($taskData['operator']) {
            case AndTask::TASK_NAME:
                return new AndTask($children);
            case OrTask::TASK_NAME:
                return new OrTask($children);
            case EqualToTask::TASK_NAME:
                return new EqualToTask($taskData['id'], $taskData['type'], $taskData['value'], $taskData['attributes'] ?? []);
            case LessThanTask::TASK_NAME:
                return new LessThanTask($taskData['id'], $taskData['type'], $taskData['value'], $taskData['attributes'] ?? []);
            case MoreThanTask::TASK_NAME:
                return new MoreThanTask($taskData['id'], $taskData['type'], $taskData['value'], $taskData['attributes'] ?? []);
            case EqualToOrMoreTask::TASK_NAME:
                return new EqualToOrMoreTask($taskData['id'], $taskData['type'], $taskData['value'], $taskData['attributes'] ?? []);
        }

        throw new \InvalidArgumentException(sprintf('Cannot build task with type: %s', $taskData['operator']));
    }
}
