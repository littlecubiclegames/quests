<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Initialization\Event;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class Event extends \Symfony\Component\EventDispatcher\Event
{
    const QUEST_ACTIVE = 'quest.active';
    /** @var QuestInterface */
    private $quest;
    /** @var Slot */
    private $slot;
    public function __construct(QuestInterface $quest, Slot $slot)
    {
        $this->quest = $quest;
        $this->slot = $slot;
    }
    public function getQuest()
    {
        return $this->quest;
    }
    public function getSlot()
    {
        return $this->slot;
    }
}
