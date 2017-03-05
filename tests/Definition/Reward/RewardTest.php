<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Definition\Reward\Reward;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\CollectorInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use PHPUnit\Framework\TestCase;

class RewardTest extends TestCase
{
    public function testGetters()
    {
        $type = 'type';
        $data = [
            'type' => $type,
            'value' => 1,
        ];

        $reward = new Reward($data);
        $this->assertSame($type, $reward->getType());
        $this->assertSame($data, $reward->getData());
    }

    public function testCollect()
    {
        $collector = $this->getMockBuilder(CollectorInterface::class)->getMock();
        $provider = $this->getMockBuilder(Provider::class)->disableOriginalConstructor()->getMock();
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $reward = new Reward(['type' => 'type']);
        $provider
            ->expects($this->once())
            ->method('getCollector')
            ->with($this->equalTo($reward))
            ->willReturn($collector);

        $collector
            ->expects($this->once())
            ->method('collect')
            ->with($this->equalTo($reward));

        $reward->collect($provider, $quest);
    }

    public function testMissingType()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Reward([]);
    }
}
