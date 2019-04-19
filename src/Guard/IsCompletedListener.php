<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Guard;

use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class IsCompletedListener implements EventSubscriberInterface
{
    /** @var RegistryInterface */
    private $questRegistry;

    public function __construct(RegistryInterface $questRegistry)
    {
        $this->questRegistry = $questRegistry;
    }

    public function validate(GuardEvent $event): void
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $questDefinition = $this->questRegistry->getQuest($quest->getQuestId());

        $isBlocked = !$questDefinition->getTask()->isFinished($quest->getProgressMap());

        $event->setBlocked($isBlocked);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            sprintf('workflow.%s.guard.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_COMPLETE) => 'validate',
        ];
    }
}
