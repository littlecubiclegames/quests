<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Slot\SlotLoaderInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class NextQuestListener implements EventSubscriberInterface
{
    /** @var SlotLoaderInterface */
    private $slotLoader;
    /** @var QuestStarter */
    private $questStarter;

    public function __construct(SlotLoaderInterface $slotLoader, QuestStarter $questStarter)
    {
        $this->slotLoader = $slotLoader;
        $this->questStarter = $questStarter;
    }

    public function triggerNextQuest(Event $event): void
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();
        $slots = $this->slotLoader->getSlotsForUser($quest->getUser());
        $slot = $slots->getSlot($quest->getSlotId());

        if ($slot) {
            $this->questStarter->triggerNext($slot, $quest->getUser(), $quest);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            sprintf('workflow.%s.enter.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::STATE_FINISHED) => 'triggerNextQuest',
            sprintf('workflow.%s.enter.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::STATE_REJECTED) => 'triggerNextQuest',
        ];
    }
}
