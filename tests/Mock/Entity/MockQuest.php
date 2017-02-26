<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Entity;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;

class MockQuest implements QuestInterface
{
    private $questId;
    private $user;
    private $state;

    /** @var MockQuestTask[] */
    private $tasks;

    public function __construct(Quest $quest, $user, string $state = QuestDefinitionInterface::STATE_AVAILABLE)
    {
        $this->questId = $quest->getId();
        $this->user = $user;
        $this->state = $state;

        $this->tasks = [];
        foreach ($quest->getTaskIds() as $taskId) {
            $this->tasks[] = new MockQuestTask($this, $taskId);
        }
    }

    public function setState(string $state)
    {
        $this->state = $state;
    }

    public function getQuestId()
    {
        return $this->questId;
    }

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

    public function getTask($taskId): TaskInterface
    {
        return array_filter($this->tasks, function (MockQuestTask $task) use ($taskId) {
            return $taskId === $task->getTaskId();
        })[0];
    }
}
