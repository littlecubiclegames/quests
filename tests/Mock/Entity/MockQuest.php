<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Mock\Entity;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;

class MockQuest implements QuestInterface
{
    private $questId;
    private $user;
    private $state;
    /** @var MockQuestTask[] */
    private $tasks;
    /** @var string */
    private $slotId;
    public function __construct(Quest $quest, $user, $state = QuestDefinitionInterface::STATE_AVAILABLE, $slotId)
    {
        $this->questId = $quest->getId();
        $this->user = $user;
        $this->state = $state;
        $this->tasks = array();
        foreach ($quest->getTaskIds() as $taskId) {
            $this->tasks[] = new MockQuestTask($this, $taskId);
        }
        $this->slotId = $slotId;
    }
    public function setState($state)
    {
        $this->state = $state;
    }
    public function getQuestId()
    {
        return $this->questId;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function getSlotId()
    {
        return $this->slotId;
    }
    public function getProgressMap()
    {
        $progressMap = array();
        foreach ($this->tasks as $task) {
            $progressMap[$task->getTaskId()] = $task->getProgress();
        }

        return $progressMap;
    }
    public function getState()
    {
        return $this->state;
    }
    public function getTask($taskId)
    {
        return array_filter($this->tasks, function (MockQuestTask $task) use ($taskId) {
            return $taskId === $task->getTaskId();
        })[0];
    }
}
