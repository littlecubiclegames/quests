<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Slot;

use Definition\Slot\Slot;

interface SlotLoaderInterface
{
    /**
     * @param mixed $userId
     * @return Slot[]
     */
    public function getSlotsForUser($userId);
}
