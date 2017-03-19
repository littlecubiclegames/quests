<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Slot\SlotLoaderInterface;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;

class QuestInitializer
{
    /** @var QuestStorageInterface */
    private $questStorage;

    /** @var ProgressListener */
    private $questProgressListener;

    /** @var SlotLoaderInterface */
    private $slotLoader;

    public function __construct(QuestStorageInterface $questStorage, ProgressListener $questProgressListener, SlotLoaderInterface $slotLoader)
    {
        $this->questStorage = $questStorage;
        $this->questProgressListener = $questProgressListener;
        $this->slotLoader = $slotLoader;
    }

    public function initialize($userId)
    {
        $slots = $this->slotLoader->getSlotsForUser($userId);

        $quests = $this->questStorage->getActiveQuests($userId);
        foreach ($quests as $quest) {
            if ($slots->isSlotAvailable($quest->getSlotId())) {
                $slots->markSlotAsUsed($quest->getSlotId());

                if ($quest->getState() === QuestDefinitionInterface::STATE_IN_PROGRESS) {
                    $this->questProgressListener->registerQuest($quest);
                }
            }
        }
    }
}
