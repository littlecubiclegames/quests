<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Reward;

class RewardBuilder
{
    public function build(array $data)
    {
        if (isset($data['rewards']) && is_array($data['rewards'])) {
            $rewards = array();
            foreach ($data['rewards'] as $rewardData) {
                $rewards[] = new Reward($rewardData);
            }
            if (count($rewards) > 1) {
                $reward = new MultipleRewards($rewards);
            } else {
                $reward = array_pop($rewards);
            }

            return $reward;
        }

        return null;
    }
}
