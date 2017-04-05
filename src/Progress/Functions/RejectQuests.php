<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\Workflow\Event\Event;

class RejectQuests implements EventHandlerFunctionInterface
{
    const NAME = 'reject-quests';
    public function handle(TaskInterface $task, Event $event)
    {
        return $task->getProgress() + 1;
    }
    public function getEventMap()
    {
        return array(sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_REJECT) => 'handle', sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_ABORT) => 'handle');
    }
}
