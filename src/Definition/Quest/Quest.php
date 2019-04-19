<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;

class Quest
{
    /** @var int */
    private $id;
    /** @var TaskInterface */
    private $task;
    /** @var array */
    private $data;
    /** @var RewardInterface|null */
    private $reward;
    /** @var TaskInterface */
    private $trigger;

    public function __construct(int $id, TaskInterface $task, array $data, TaskInterface $trigger, ?RewardInterface $reward = null)
    {
        $this->id = $id;
        $this->task = $task;
        $this->data = $data;
        $this->reward = $reward;
        $this->trigger = $trigger;
    }

    public function getId(): int
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

    public function getTrigger(): TaskInterface
    {
        return $this->trigger;
    }
}
