<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestBuilderInterface;
use LittleCubicleGames\Quests\Initialization\QuestStarter;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QuestStarterTest extends TestCase
{
    /** @var QuestStarter */
    private $questStarter;
    private $registry;
    private $questBuilder;
    private $questStorage;
    protected function setUp()
    {
        $this->registry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $this->questBuilder = $this->getMockBuilder(QuestBuilderInterface::class)->getMock();
        $this->questStorage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->questStarter = new QuestStarter($this->registry, $this->questBuilder, $this->questStorage, $dispatcher);
    }
    public function testTriggerNext()
    {
        $userId = 1;
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $nextQuestData = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $nextQuest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->once())->method('getUser')->willReturn($userId);
        $this->registry->expects($this->once())->method('getNextQuest')->with($this->equalTo($slot), $this->equalTo($quest))->willReturn($nextQuestData);
        $this->questBuilder->expects($this->once())->method('buildQuest')->with($this->equalTo($nextQuestData), $this->equalTo($slot), $this->equalTo($userId))->willReturn($nextQuest);
        $this->questStorage->expects($this->once())->method('save')->with($this->equalTo($nextQuest));
        $this->questStarter->triggerNext($slot, $quest);
    }
    public function testTriggerNextNoNext()
    {
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->never())->method('getUser');
        $this->registry->expects($this->once())->method('getNextQuest')->with($this->equalTo($slot), $this->equalTo($quest))->willReturn(null);
        $this->questBuilder->expects($this->never())->method('buildQuest');
        $this->questStorage->expects($this->never())->method('save');
        $this->questStarter->triggerNext($slot, $quest);
    }
}
