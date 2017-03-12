<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Reward\Collect;

use LittleCubicleGames\Quests\Definition\Reward\RewardInterface;

interface CollectorInterface
{
    public function collect(RewardInterface $reward);
    public function getType();
}
