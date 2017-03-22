<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Slot\Slot;

interface CollectibleRegistryInterface extends RegistryInterface
{
    public function supports($id);
    public function supportsSlot(Slot $slot);
}
