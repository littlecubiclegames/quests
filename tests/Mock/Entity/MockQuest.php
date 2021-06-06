<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Entity;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Entity\TaskInterface;

class MockQuest implements QuestInterface
{
    /** @var int */
    private $questId;
    /** @var int */
    private $user;
    /** @var string */
    private $state;

    /** @var MockQuestTask[] */
    private $tasks;
    /** @var string */
    private $slotId;

    public function __construct(Quest $quest, int $user, string $state, string $slotId)
    {
        $this->questId = $quest->getId();
        $this->user = $user;
        $this->state = $state;

        $this->tasks = [];
        foreach ($quest->getTaskIds() as $taskId) {
            $this->tasks[] = new MockQuestTask($this, $taskId);
        }
        $this->slotId = $slotId;
    }

    public function getId(): int
    {
        return $this->questId;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getQuestId(): int
    {
        return $this->questId;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function getSlotId(): string
    {
        return $this->slotId;
    }

    /**
     * @return int[]
     */
    public function getProgressMap(): array
    {
        $progressMap = [];
        foreach ($this->tasks as $task) {
            $progressMap[$task->getTaskId()] = $task->getProgress();
        }

        return $progressMap;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getTask(int $taskId): TaskInterface
    {
        return array_filter($this->tasks, function (MockQuestTask $task) use ($taskId): bool {
            return $taskId === $task->getTaskId();
        })[0];
    }
}
