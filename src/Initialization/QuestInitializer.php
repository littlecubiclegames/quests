<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Initialization\Event\Event;
use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Slot\SlotLoaderInterface;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QuestInitializer
{
    /** @var QuestStorageInterface */
    private $questStorage;
    /** @var ProgressListener */
    private $questProgressListener;
    /** @var SlotLoaderInterface */
    private $slotLoader;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var QuestStarter */
    private $questStarter;

    public function __construct(QuestStorageInterface $questStorage, ProgressListener $questProgressListener, SlotLoaderInterface $slotLoader, QuestStarter $questStarter, EventDispatcherInterface $dispatcher)
    {
        $this->questStorage = $questStorage;
        $this->questProgressListener = $questProgressListener;
        $this->slotLoader = $slotLoader;
        $this->questStarter = $questStarter;
        $this->dispatcher = $dispatcher;
    }

    public function initialize($userId)
    {
        $slots = $this->slotLoader->getSlotsForUser($userId);

        $quests = $this->questStorage->getActiveQuests($userId);
        foreach ($quests as $quest) {
            if ($slots->isSlotAvailable($quest->getSlotId())) {
                $slot = $slots->getSlot($quest->getSlotId());
                $slots->markSlotAsUsed($quest->getSlotId());

                if ($quest->getState() === QuestDefinitionInterface::STATE_IN_PROGRESS) {
                    $this->questProgressListener->registerQuest($quest);
                }

                $this->dispatcher->dispatch(Event::QUEST_ACTIVE, new Event($quest, $slot));
            }
        }

        foreach ($slots->getUnusedSlots() as $slot) {
            $this->questStarter->triggerNext($slot, $userId, null);
        }
    }
}
