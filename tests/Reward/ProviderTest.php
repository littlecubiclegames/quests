<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Reward;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Reward\Collect\CollectorInterface;
use LittleCubicleGames\Quests\Reward\Collect\InvalidQuestRewardCollectorException;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function testGetCollector()
    {
        $rewardType = 'reward';

        $reward = $this->getMockBuilder(RewardInterface::class)->getMock();
        $reward
            ->expects($this->once())
            ->method('getType')
            ->willReturn($rewardType);

        $collector = $this->getMockBuilder(CollectorInterface::class)->getMock();
        $collector
            ->expects($this->once())
            ->method('getType')
            ->willReturn($rewardType);

        $provider = new Provider([$collector]);
        $actual = $provider->getCollector($reward);

        $this->assertSame($collector, $actual);
    }

    public function testGetCollectorNotFound()
    {
        $rewardType = 'reward';

        $reward = $this->getMockBuilder(RewardInterface::class)->getMock();
        $reward
            ->expects($this->once())
            ->method('getType')
            ->willReturn($rewardType);

        $collector = $this->getMockBuilder(CollectorInterface::class)->getMock();
        $collector
            ->expects($this->any())
            ->method('getType')
            ->willReturn('other');

        $this->expectException(InvalidQuestRewardCollectorException::class);

        $provider = new Provider([$collector]);
        $provider->getCollector($reward);
    }
}
