<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Reward\Collect;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;

interface CollectorInterface
{
    public function collect(RewardInterface $reward, QuestInterface $quest): void;

    public function getType(): string;
}
