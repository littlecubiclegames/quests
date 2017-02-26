<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Entity;

use LittleCubicleGames\Quests\Entity\TaskInterface;

class MockQuestTask implements TaskInterface
{
    private $quest;
    private $taskId;
    private $progress;

    public function __construct(MockQuest $quest, $taskId, int $progress = 0)
    {
        $this->quest = $quest;
        $this->taskId = $taskId;
        $this->progress = $progress;
    }

    public function updateProgress(int $progress)
    {
        $this->progress = $progress;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function getProgress() : int
    {
        return $this->progress;
    }
}
