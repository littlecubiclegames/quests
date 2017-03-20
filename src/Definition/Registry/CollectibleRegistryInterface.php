<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Slot\Slot;

interface CollectibleRegistryInterface extends RegistryInterface
{
    public function supports($id): bool;
    public function supportsSlot(Slot $slot): bool;
}
