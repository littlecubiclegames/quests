<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Slot;

use LittleCubicleGames\Quests\Definition\Slot\SlotCollection;

interface SlotLoaderInterface
{
    /**
     * @param mixed $userId
     * @return SlotCollection
     */
    public function getSlotsForUser($userId): SlotCollection;
}
