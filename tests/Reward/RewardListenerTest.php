<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Reward;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use LittleCubicleGames\Quests\Reward\RewardListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class RewardListenerTest extends TestCase
{
    /** @var RewardListener */
    private $listener;
    private $provider;
    private $registry;
    protected function setUp()
    {
        $this->provider = $this->getMockBuilder(Provider::class)->disableOriginalConstructor()->getMock();
        $this->registry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $this->listener = new RewardListener($this->registry, $this->provider);
    }
    public function testCollectNoReward()
    {
        $questId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->once())->method('getQuestId')->willReturn($questId);
        $questDefinition = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $questDefinition->expects($this->once())->method('hasReward')->willReturn(false);
        $questDefinition->expects($this->never())->method('getReward');
        $this->registry->expects($this->once())->method('getQuest')->with($this->equalTo($questId))->willReturn($questDefinition);
        $this->listener->collect(new Event($quest, new Marking(), new Transition('transition', '', '')));
    }
    public function testCollect()
    {
        $questId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest->expects($this->once())->method('getQuestId')->willReturn($questId);
        $reward = $this->getMockBuilder(RewardInterface::class)->getMock();
        $reward->expects($this->once())->method('collect')->with($this->equalTo($this->provider), $this->equalTo($quest));
        $questDefinition = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $questDefinition->expects($this->once())->method('hasReward')->willReturn(true);
        $questDefinition->expects($this->once())->method('getReward')->willReturn($reward);
        $this->registry->expects($this->once())->method('getQuest')->with($this->equalTo($questId))->willReturn($questDefinition);
        $this->listener->collect(new Event($quest, new Marking(), new Transition('transition', '', '')));
    }
}
