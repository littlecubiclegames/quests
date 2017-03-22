<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

interface RegistryInterface
{
    public function getQuest($id);
    public function getNextQuest(Slot $slot, QuestInterface $quest = null);
}
