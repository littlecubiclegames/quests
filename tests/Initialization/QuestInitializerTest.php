<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotCollection;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestBuilderInterface;
use LittleCubicleGames\Quests\Initialization\QuestInitializer;
use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Slot\SlotLoaderInterface;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QuestInitializerTest extends TestCase
{
    /** @var QuestInitializer */
    private $initializer;
    private $storage;
    private $progressListener;
    private $slotLoader;
    private $registry;
    private $questBuilder;
    protected function setUp()
    {
        $this->storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $this->progressListener = $this->getMockBuilder(ProgressListener::class)->disableOriginalConstructor()->getMock();
        $this->slotLoader = $this->getMockBuilder(SlotLoaderInterface::class)->getMock();
        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->registry = $this->getMockBuilder(Registry::class)->disableOriginalConstructor()->getMock();
        $this->questBuilder = $this->getMockBuilder(QuestBuilderInterface::class)->getMock();
        $this->initializer = new QuestInitializer($this->storage, $this->progressListener, $this->slotLoader, $dispatcher, $this->registry, $this->questBuilder);
    }
    public function testInitialize()
    {
        $userId = 1;
        $slot1 = 'slot1';
        $slot2 = 'slot2';
        $quest1 = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest1->expects($this->once())->method('getState')->willReturn(QuestDefinitionInterface::STATE_AVAILABLE);
        $quest1->expects($this->any())->method('getSlotId')->willReturn($slot1);
        $quest2 = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest2->expects($this->once())->method('getState')->willReturn(QuestDefinitionInterface::STATE_IN_PROGRESS);
        $quest2->expects($this->any())->method('getSlotId')->willReturn($slot2);
        $this->storage->expects($this->once())->method('getActiveQuests')->with($this->equalTo($userId))->willReturn(array($quest1, $quest2));
        $this->slotLoader->expects($this->once())->method('getSlotsForUser')->with($this->equalTo($userId))->willReturn(new SlotCollection(array($slot1 => new Slot($slot1, 'registry'), $slot2 => new Slot($slot2, 'registry'))));
        $this->progressListener->expects($this->once())->method('registerQuest')->with($this->equalTo($quest2));
        $this->initializer->initialize($userId);
    }
    public function testInitializeUnavailableSlot()
    {
        $userId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->never())->method('getState')->willReturn(QuestDefinitionInterface::STATE_AVAILABLE);
        $quest->expects($this->any())->method('getSlotId')->willReturn('slot');
        $this->slotLoader->expects($this->once())->method('getSlotsForUser')->with($this->equalTo($userId))->willReturn(new SlotCollection(array('otherslot' => new Slot('otherslot', 'registry'))));
        $this->storage->expects($this->once())->method('getActiveQuests')->with($this->equalTo($userId))->willReturn(array());
        $this->progressListener->expects($this->never())->method('registerQuest');
        $this->initializer->initialize($userId);
    }
}
