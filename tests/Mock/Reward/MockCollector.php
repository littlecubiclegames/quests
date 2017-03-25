<?php

namespace LittleCubicleGames\Tests\Quests\Mock\Reward;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\CollectorInterface;

class MockCollector implements CollectorInterface
{
    const TYPE = 'mock';

    public function collect(RewardInterface $reward, QuestInterface $quest)
    {
    }

    public function getType()
    {
        return self::TYPE;
    }
}
