<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotCollection;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestInitializer;
use LittleCubicleGames\Quests\Initialization\QuestStarter;
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
    /** @var QuestStorageInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $storage;
    /** @var ProgressListener&\PHPUnit\Framework\MockObject\MockObject */
    private $progressListener;
    /** @var SlotLoaderInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $slotLoader;
    /** @var QuestStarter&\PHPUnit\Framework\MockObject\MockObject */
    private $questStarter;
    /** @var EventDispatcherInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $this->progressListener = $this->getMockBuilder(ProgressListener::class)->disableOriginalConstructor()->getMock();
        $this->slotLoader = $this->getMockBuilder(SlotLoaderInterface::class)->getMock();
        $this->questStarter = $this->getMockBuilder(QuestStarter::class)->disableOriginalConstructor()->getMock();
        $this->dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        $this->initializer = new QuestInitializer($this->storage, $this->progressListener, $this->slotLoader, $this->questStarter, $this->dispatcher);
    }

    public function testInitialize(): void
    {
        $userId = 1;
        $slot1 = 'slot1';
        $slot2 = 'slot2';

        $quest1 = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest1
            ->expects($this->any())
            ->method('getState')
            ->willReturn(QuestDefinitionInterface::STATE_AVAILABLE);
        $quest1
            ->expects($this->any())
            ->method('getSlotId')
            ->willReturn($slot1);
        $quest2 = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest2
            ->expects($this->any())
            ->method('getState')
            ->willReturn(QuestDefinitionInterface::STATE_IN_PROGRESS);
        $quest2
            ->expects($this->any())
            ->method('getSlotId')
            ->willReturn($slot2);

        $this->storage
            ->expects($this->once())
            ->method('getActiveQuests')
            ->with($this->equalTo($userId))
            ->willReturn([
                $quest1,
                $quest2,
            ]);

        $slotCollection = new SlotCollection([
            $slot1 => new Slot($slot1, 'registry'),
            $slot2 => new Slot($slot2, 'registry'),
        ]);
        $this->slotLoader
            ->expects($this->once())
            ->method('getSlotsForUser')
            ->with($this->equalTo($userId))
            ->willReturn($slotCollection);

        $this->progressListener
            ->expects($this->once())
            ->method('registerQuest')
            ->with($this->equalTo($quest2));

        $this->initializer->initialize($userId);
    }

    public function testInitializeUnavailableSlot(): void
    {
        $userId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->never())
            ->method('getState')
            ->willReturn(QuestDefinitionInterface::STATE_AVAILABLE);
        $quest
            ->expects($this->any())
            ->method('getSlotId')
            ->willReturn('slot');

        $this->slotLoader
            ->expects($this->once())
            ->method('getSlotsForUser')
            ->with($this->equalTo($userId))
            ->willReturn(new SlotCollection([
                'otherslot' => new Slot('otherslot', 'registry'),
            ]));

        $this->storage
            ->expects($this->once())
            ->method('getActiveQuests')
            ->with($this->equalTo($userId))
            ->willReturn([]);

        $this->progressListener
            ->expects($this->never())
            ->method('registerQuest');

        $this->initializer->initialize($userId);
    }
}
