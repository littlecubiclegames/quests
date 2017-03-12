<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;

class MultipleRewards implements RewardInterface
{
    /** @var RewardInterface[] */
    private $rewards;
    public function __construct(array $rewards)
    {
        $this->rewards = $rewards;
    }
    public function collect(Provider $rewardCollectorProvider, QuestInterface $quest)
    {
        foreach ($this->rewards as $reward) {
            $reward->collect($rewardCollectorProvider, $quest);
        }
    }
    public function getType()
    {
        return self::class;
    }
}
