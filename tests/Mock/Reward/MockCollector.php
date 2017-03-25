<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Reward;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\CollectorInterface;

class MockCollector implements CollectorInterface
{
    const TYPE = 'mock';

    public function collect(RewardInterface $reward, QuestInterface $quest): void
    {
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
