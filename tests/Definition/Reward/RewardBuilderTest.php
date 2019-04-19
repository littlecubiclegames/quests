<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Definition\Reward\MultipleRewards;
use LittleCubicleGames\Quests\Definition\Reward\Reward;
use LittleCubicleGames\Quests\Definition\Reward\RewardBuilder;
use PHPUnit\Framework\TestCase;

class RewardBuilderTest extends TestCase
{
    /** @var RewardBuilder */
    private $rewardBuilder;

    protected function setUp(): void
    {
        $this->rewardBuilder = new RewardBuilder();
    }

    public function testBuildNull(): void
    {
        $this->assertNull($this->rewardBuilder->build([]));
    }

    public function testBuildSingle(): void
    {
        $reward = $this->rewardBuilder->build(['rewards' => [['type' => 'type']]]);
        $this->assertInstanceOf(Reward::class, $reward);
        $this->assertSame('type', $reward->getType());
    }

    public function testBuildMultiple(): void
    {
        $reward = $this->rewardBuilder->build(['rewards' => [['type' => 'type'], ['type' => 'type2']]]);
        $this->assertInstanceOf(MultipleRewards::class, $reward);
    }
}
