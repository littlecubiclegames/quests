<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Log;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class QuestLogListener implements EventSubscriberInterface
{
    /** @var QuestLoggerInterface[] */
    private $questLogger;

    public function __construct(iterable $questLogger)
    {
        $this->questLogger = $questLogger;
    }

    public function logChange(Event $event): void
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        foreach ($this->questLogger as $logger) {
            $logger->log($quest, $quest->getState(), $event->getTransition()->getName());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            sprintf('workflow.%s.transition', QuestDefinitionInterface::WORKFLOW_NAME) => 'logChange',
        ];
    }
}
