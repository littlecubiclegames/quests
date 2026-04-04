<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Reward\Collect;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;

class Provider
{
    /** @var iterable<CollectorInterface> */
    private $collectors;

    /** @param iterable<CollectorInterface> $collectors */
    public function __construct(iterable $collectors)
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
        }, (array) $this->collectors);

        throw new InvalidQuestRewardCollectorException(sprintf("Requested collector: '%s', available collectors: [%s]", $type, implode(',', $availableCollectors)));
    }
}
