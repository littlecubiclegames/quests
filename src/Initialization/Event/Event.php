<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Initialization\Event;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class Event extends \Symfony\Component\EventDispatcher\Event
{
    const QUEST_ACTIVE = 'quest.active';

    /** @var QuestInterface */
    private $quest;
    /** @var Slot|null */
    private $slot;

    public function __construct(QuestInterface $quest, ?Slot $slot)
    {
        $this->quest = $quest;
        $this->slot = $slot;
    }

    public function getQuest(): QuestInterface
    {
        return $this->quest;
    }

    public function getSlot(): ?Slot
    {
        return $this->slot;
    }
}
