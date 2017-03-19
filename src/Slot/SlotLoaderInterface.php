<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Slot;

use LittleCubicleGames\Quests\Definition\Slot\SlotCollection;

interface SlotLoaderInterface
{
    /**
     * @param mixed $userId
     * @return SlotCollection
     */
    public function getSlotsForUser($userId);
}
