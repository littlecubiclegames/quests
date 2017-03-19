<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;

class Quest
{
    /** @var mixed */
    private $id;
    /** @var TaskInterface */
    private $task;
    /** @var RewardInterface */
    private $reward;
    public function __construct($id, TaskInterface $task, RewardInterface $reward = null)
    {
        $this->id = $id;
        $this->task = $task;
        $this->reward = $reward;
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
    public function getTaskEventMap()
    {
        return array();
    }
}
