<?php

namespace LittleCubicleGames\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;

class Quest
{
    /** @var mixed */
    private $id;
    /** @var TaskInterface */
    private $task;
    /** @var array */
    private $data;
    /** @var RewardInterface */
    private $reward;
    /** @var TaskInterface */
    private $trigger;

    public function __construct($id, TaskInterface $task, array $data, TaskInterface $trigger, RewardInterface $reward = null)
    {
        $this->id = $id;
        $this->task = $task;
        $this->data = $data;
        $this->reward = $reward;
        $this->trigger = $trigger;
    }
    public function getId()
    {
        return $this->id;
    }
    public function hasReward()
    {
        return $this->reward !== null;
    }
    public function getReward()
    {
        return $this->reward;
    }
    public function getTask()
    {
        return $this->task;
    }
    public function getTaskIds()
    {
        return array_keys($this->task->getTaskIdTypes());
    }
    public function getData()
    {
        return $this->data;
    }
    public function getTrigger()
    {
        return $this->trigger;
    }
}
