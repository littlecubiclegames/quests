<?php

namespace LittleCubicleGames\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Task\TaskInterface;

class Quest
{
    /** @var mixed */
    private $id;

    /** @var TaskInterface */
    private $task;

    public function __construct($id, TaskInterface $task)
    {
        $this->id = $id;
        $this->task = $task;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTask() : TaskInterface
    {
        return $this->task;
    }

    public function getTaskIds() : array
    {
        return array_keys($this->task->getTaskIdTypes());
    }

    public function getTaskEventMap() : array
    {
        return [];
    }
}
