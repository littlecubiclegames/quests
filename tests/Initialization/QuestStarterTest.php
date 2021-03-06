<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestBuilderInterface;
use LittleCubicleGames\Quests\Initialization\QuestStarter;
use LittleCubicleGames\Quests\QuestAdvancer;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QuestStarterTest extends TestCase
{
    /** @var QuestStarter */
    private $questStarter;
    /** @var RegistryInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $registry;
    /** @var QuestBuilderInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $questBuilder;
    /** @var QuestStorageInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $questStorage;
    /** @var EventDispatcherInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $dispatcher;
    /** @var QuestAdvancer&\PHPUnit\Framework\MockObject\MockObject */
    private $questAdvancer;

    protected function setUp(): void
    {
        $this->registry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $this->questBuilder = $this->getMockBuilder(QuestBuilderInterface::class)->getMock();
        $this->questStorage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $this->dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->questAdvancer = $this->getMockBuilder(QuestAdvancer::class)->disableOriginalConstructor()->getMock();
        $this->questStarter = new QuestStarter($this->registry, $this->questBuilder, $this->questStorage, $this->dispatcher, $this->questAdvancer, false);
    }

    public function testTriggerNext(): void
    {
        $userId = 1;
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $nextQuestData = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $nextQuest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $this->registry
            ->expects($this->once())
            ->method('getNextQuest')
            ->with($this->equalTo($userId), $this->equalTo($slot), $this->equalTo($quest))
            ->willReturn($nextQuestData);

        $this->questBuilder
            ->expects($this->once())
            ->method('buildQuest')
            ->with($this->equalTo($nextQuestData), $this->equalTo($slot), $this->equalTo($userId))
            ->willReturn($nextQuest);

        $this->questStorage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($nextQuest));

        $this->questStarter->triggerNext($slot, $userId, $quest);
    }

    public function testTriggerNextNoPrevious(): void
    {
        $userId = 1;
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $nextQuestData = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $nextQuest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $this->registry
            ->expects($this->once())
            ->method('getNextQuest')
            ->with($this->equalTo($userId), $this->equalTo($slot), $this->equalTo(null))
            ->willReturn($nextQuestData);

        $this->questBuilder
            ->expects($this->once())
            ->method('buildQuest')
            ->with($this->equalTo($nextQuestData), $this->equalTo($slot), $this->equalTo($userId))
            ->willReturn($nextQuest);

        $this->questStorage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($nextQuest));

        $this->questStarter->triggerNext($slot, $userId, null);
    }

    public function testTriggerNextNoNext(): void
    {
        $userId = 1;
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $quest
            ->expects($this->never())
            ->method('getUser');

        $this->registry
            ->expects($this->once())
            ->method('getNextQuest')
            ->with($this->equalTo($userId), $this->equalTo($slot), $this->equalTo($quest))
            ->willReturn(null);

        $this->questBuilder
            ->expects($this->never())
            ->method('buildQuest');

        $this->questStorage
            ->expects($this->never())
            ->method('save');

        $this->questStarter->triggerNext($slot, $userId, $quest);
    }
}
