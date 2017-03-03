<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Definition\Reward\MultipleRewards;
use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use PHPUnit\Framework\TestCase;

class MultipleRewardsTest extends TestCase
{
    public function testCollect()
    {
        $provider = $this->getMockBuilder(Provider::class)->disableOriginalConstructor()->getMock();
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $rewards = [];
        for ($i = 0; $i < 2; $i++) {
            $rewardMock = $this->getMockBuilder(RewardInterface::class)->getMock();
            $rewardMock
                ->expects($this->once())
                ->method('collect')
                ->with($this->equalTo($provider), $this->equalTo($quest));
            $rewards[] = $rewardMock;
        }

        $reward = new MultipleRewards($rewards);
        $reward->collect($provider, $quest);
    }
}
