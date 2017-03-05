<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Reward;

class RewardBuilder
{
    public function build(array $data): ?RewardInterface
    {
        if (isset($data['rewards']) && is_array($data['rewards'])) {
            $rewards = [];
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
