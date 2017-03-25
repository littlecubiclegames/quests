<?php declare(strict_types=1);

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

    public function __construct($id, TaskInterface $task, array $data, ?RewardInterface $reward = null)
    {
        $this->id = $id;
        $this->task = $task;
        $this->data = $data;
        $this->reward = $reward;
    }

    public function getId()
    {
        return $this->id;
    }

    public function hasReward(): bool
    {
        return $this->reward !== null;
    }

    public function getReward(): ?RewardInterface
    {
        return $this->reward;
    }

    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    public function getTaskIds(): array
    {
        return array_keys($this->task->getTaskIdTypes());
    }

    public function getData(): array
    {
        return $this->data;
    }
}
