<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Reward\Collect;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;

interface CollectorInterface
{
    public function collect(RewardInterface $reward): void;

    public function getType(): string;
}
