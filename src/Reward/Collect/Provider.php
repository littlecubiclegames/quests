<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Reward\Collect;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;

class Provider
{
    /** @var CollectorInterface[] */
    private $collectors;

    public function __construct(array $collectors)
    {
        $this->collectors = $collectors;
    }

    public function getCollector(RewardInterface $reward): CollectorInterface
    {
        $type = $reward->getType();
        foreach ($this->collectors as $collector) {
            if ($collector->getType() === $type) {
                return $collector;
            }
        }

        $availableCollectors = array_map(function (CollectorInterface $collector): string {
            return $collector->getType();
        }, $this->collectors);

        throw new InvalidQuestRewardCollectorException(sprintf("Requested collector: '%s', available collectors: [%s]", $type, implode(',', $availableCollectors)));
    }
}
