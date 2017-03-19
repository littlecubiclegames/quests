<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
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
    public function getCollector(RewardInterface $reward)
    {
        $type = $reward->getType();
        foreach ($this->collectors as $collector) {
            if ($collector->getType() === $type) {
                return $collector;
            }
        }
        $availableCollectors = array_map(function (CollectorInterface $collector) {
            return $collector->getType();
        }, $this->collectors);
        throw new InvalidQuestRewardCollectorException(sprintf('Requested collector: \'%s\', available collectors: [%s]', $type, implode(',', $availableCollectors)));
    }
}
