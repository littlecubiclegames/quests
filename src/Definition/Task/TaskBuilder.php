<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Task;

class TaskBuilder
{
    public function build(array $taskData)
    {
        $children = array();
        if (isset($taskData['children'])) {
            $children = array_map(function (array $taskData) {
                return $this->build($taskData);
            }, $taskData['children']);
        }
        $attributes = isset($taskData['attributes']) ? $taskData['attributes'] : [];
        switch ($taskData['operator']) {
            case AndTask::TASK_NAME:
                return new AndTask($children);
            case OrTask::TASK_NAME:
                return new OrTask($children);
            case EqualToTask::TASK_NAME:
                return new EqualToTask($taskData['id'], $taskData['type'], $taskData['value'], $attributes);
            case LessThanTask::TASK_NAME:
                return new LessThanTask($taskData['id'], $taskData['type'], $taskData['value'], $attributes);
            case MoreThanTask::TASK_NAME:
                return new MoreThanTask($taskData['id'], $taskData['type'], $taskData['value'], $attributes);
            case EqualToOrMoreTask::TASK_NAME:
                return new EqualToOrMoreTask($taskData['id'], $taskData['type'], $taskData['value'], $attributes);
        }
        throw new \InvalidArgumentException(sprintf('Cannot build task with type: %s', $taskData['operator']));
    }
}
