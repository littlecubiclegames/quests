<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Entity;

use LittleCubicleGames\Quests\Entity\TaskInterface;

class MockQuestTask implements TaskInterface
{
    /** @var MockQuest */
    private $quest;
    /** @var int */
    private $taskId;
    /** @var int */
    private $progress;

    public function __construct(MockQuest $quest, int $taskId, int $progress = 0)
    {
        $this->quest = $quest;
        $this->taskId = $taskId;
        $this->progress = $progress;
    }

    public function updateProgress(int $progress): void
    {
        $this->progress = $progress;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }
}
