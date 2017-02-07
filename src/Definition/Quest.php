<?php

namespace LittleCubicleGames\Quests\Definition;

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

    public function getTask() : TaskInterface
    {
        return $this->task;
    }
}
