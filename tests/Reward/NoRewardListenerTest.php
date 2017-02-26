<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Reward;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\NoRewardListener;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class NoRewardListenerTest extends TestCase
{
    /** @var NoRewardListener */
    private $listener;
    private $registry;
    private $worfklow;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder(Registry::class)->disableOriginalConstructor()->getMock();
        $this->worfklow = $this->getMockBuilder(Workflow::class)->disableOriginalConstructor()->getMock();

        $this->listener = new NoRewardListener($this->registry, $this->worfklow);
    }

    public function testHasNoReward()
    {
        $questId = 1;

        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->once())
            ->method('getQuestId')
            ->willReturn($questId);

        $definition = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $definition
            ->expects($this->once())
            ->method('hasReward')
            ->willReturn(false);

        $this->registry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn($definition);

        $this->worfklow
            ->expects($this->once())
            ->method('can')
            ->with($this->equalTo($quest), $this->equalTo(QuestDefinitionInterface::TRANSITION_COLLECT_REWARD))
            ->willReturn(true);

        $this->worfklow
            ->expects($this->once())
            ->method('apply')
            ->with($this->equalTo($quest), $this->equalTo(QuestDefinitionInterface::TRANSITION_COLLECT_REWARD));

        $event = new Event($quest, new Marking(), new Transition('transition', '', ''));
        $this->listener->validate($event);
    }

    public function testHasReward()
    {
        $questId = 1;

        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->once())
            ->method('getQuestId')
            ->willReturn($questId);

        $definition = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $definition
            ->expects($this->once())
            ->method('hasReward')
            ->willReturn(true);

        $this->registry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn($definition);

        $this->worfklow
            ->expects($this->never())
            ->method('can');

        $this->worfklow
            ->expects($this->never())
            ->method('apply');

        $event = new Event($quest, new Marking(), new Transition('transition', '', ''));
        $this->listener->validate($event);
    }
}
