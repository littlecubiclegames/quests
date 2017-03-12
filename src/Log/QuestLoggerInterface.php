<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Log;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestLoggerInterface
{
    public function log(QuestInterface $quest, $previousState, $newState);
}
