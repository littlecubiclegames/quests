<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Mock\Entity;

use LittleCubicleGames\Quests\Entity\TaskInterface;

class MockQuestTask implements TaskInterface
{
    private $quest;
    private $taskId;
    private $progress;
    public function __construct(MockQuest $quest, $taskId, $progress = 0)
    {
        $this->quest = $quest;
        $this->taskId = $taskId;
        $this->progress = $progress;
    }
    public function updateProgress($progress)
    {
        $this->progress = $progress;
    }
    public function getTaskId()
    {
        return $this->taskId;
    }
    public function getProgress()
    {
        return $this->progress;
    }
}
