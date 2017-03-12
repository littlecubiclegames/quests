<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;

class Reward implements RewardInterface
{
    /** @var string */
    private $type;
    /** @var mixed[] */
    private $data;
    public function __construct(array $data)
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('reward definition does not contain type');
        }
        $this->type = $data['type'];
        $this->data = $data;
    }
    public function collect(Provider $rewardCollectorProvider, QuestInterface $quest)
    {
        $rewardCollectorProvider->getCollector($this)->collect($this, $quest);
    }
    public function getData()
    {
        return $this->data;
    }
    public function getType()
    {
        return $this->type;
    }
}
