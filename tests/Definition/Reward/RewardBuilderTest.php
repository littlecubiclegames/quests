<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Definition\Reward\MultipleRewards;
use LittleCubicleGames\Quests\Definition\Reward\Reward;
use LittleCubicleGames\Quests\Definition\Reward\RewardBuilder;
use PHPUnit\Framework\TestCase;

class RewardBuilderTest extends TestCase
{
    /** @var RewardBuilder */
    private $rewardBuilder;
    protected function setUp()
    {
        $this->rewardBuilder = new RewardBuilder();
    }
    public function testBuildNull()
    {
        $this->assertNull($this->rewardBuilder->build([]));
    }
    public function testBuildSingle()
    {
        $reward = $this->rewardBuilder->build(['rewards' => [['type' => 'type']]]);
        $this->assertInstanceOf(Reward::class, $reward);
        $this->assertSame('type', $reward->getType());
    }
    public function testBuildMultiple()
    {
        $reward = $this->rewardBuilder->build(['rewards' => [['type' => 'type'], ['type' => 'type2']]]);
        $this->assertInstanceOf(MultipleRewards::class, $reward);
    }
}
