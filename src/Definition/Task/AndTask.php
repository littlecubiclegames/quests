<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Task;

class AndTask implements TaskInterface
{
    const TASK_NAME = 'and';
    /** @var TaskInterface[] */
    private $tasks;
    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }
    public function isFinished(array $progressMap)
    {
        foreach ($this->tasks as $task) {
            if (!$task->isFinished($progressMap)) {
                return false;
            }
        }

        return true;
    }
    public function getTaskIdTypes()
    {
        return array_reduce($this->tasks, function ($map, TaskInterface $task) {
            return $map + $task->getTaskIdTypes();
        }, array());
    }

    public function getTaskIdAttributes()
    {
        return array_reduce($this->tasks, function ($map, TaskInterface $task) {
            return $map + $task->getTaskIdAttributes();
        }, []);
    }
}
