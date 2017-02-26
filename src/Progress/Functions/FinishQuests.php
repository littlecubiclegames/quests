<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\Workflow\Event\Event;

class FinishQuests implements HandlerFunctionInterface
{
    const NAME = 'finish-quests';

    public function handle(TaskInterface $task, Event $event)
    {
        return $task->getProgress() + 1;
    }

    public function getEventMap(): array
    {
        return [
            sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD) => 'handle',
        ];
    }
}
