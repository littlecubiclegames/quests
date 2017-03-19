<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
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
        $rewards = array();
        for ($i = 0; $i < 2; $i++) {
            $rewardMock = $this->getMockBuilder(RewardInterface::class)->getMock();
            $rewardMock->expects($this->once())->method('collect')->with($this->equalTo($provider), $this->equalTo($quest));
            $rewards[] = $rewardMock;
        }
        $reward = new MultipleRewards($rewards);
        $reward->collect($provider, $quest);
    }
}
