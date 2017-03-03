<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;

interface RewardInterface
{
    public function collect(Provider $rewardCollectorProvider, QuestInterface $quest): void;

    public function getType(): string;
}
