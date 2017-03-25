<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotCollection;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\NextQuestListener;
use LittleCubicleGames\Quests\Initialization\QuestStarter;
use LittleCubicleGames\Quests\Slot\SlotLoaderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class NextQuestListenerTest extends TestCase
{
    /** @var NextQuestListener */
    private $listener;
    private $slotLoader;
    private $questStarter;
    protected function setUp()
    {
        $this->slotLoader = $this->getMockBuilder(SlotLoaderInterface::class)->getMock();
        $this->questStarter = $this->getMockBuilder(QuestStarter::class)->disableOriginalConstructor()->getMock();
        $this->listener = new NextQuestListener($this->slotLoader, $this->questStarter);
    }
    public function testTrigger()
    {
        $userId = 1;
        $slotId = 'slotId';
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->exactly(2))->method('getUser')->willReturn($userId);
        $quest->expects($this->once())->method('getSlotId')->willReturn($slotId);
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $slots = $this->getMockBuilder(SlotCollection::class)->disableOriginalConstructor()->getMock();
        $slots->expects($this->once())->method('getSlot')->with($this->equalTo($slotId))->willReturn($slot);
        $this->slotLoader->expects($this->once())->method('getSlotsForUser')->with($this->equalTo($userId))->willReturn($slots);
        $this->questStarter->expects($this->once())->method('triggerNext')->with($this->equalTo($slot), $this->equalTo($userId), $this->equalTo($quest));
        $event = new Event($quest, new Marking(), new Transition('transition', '', ''));
        $this->listener->triggerNextQuest($event);
    }
    public function testTriggerSlotUnavailable()
    {
        $userId = 1;
        $slotId = 'slotId';
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->once())->method('getUser')->willReturn($userId);
        $quest->expects($this->once())->method('getSlotId')->willReturn($slotId);
        $slots = $this->getMockBuilder(SlotCollection::class)->disableOriginalConstructor()->getMock();
        $slots->expects($this->once())->method('getSlot')->with($this->equalTo($slotId))->willReturn(null);
        $this->slotLoader->expects($this->once())->method('getSlotsForUser')->with($this->equalTo($userId))->willReturn($slots);
        $this->questStarter->expects($this->never())->method('triggerNext');
        $event = new Event($quest, new Marking(), new Transition('transition', '', ''));
        $this->listener->triggerNextQuest($event);
    }
}
