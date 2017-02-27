<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Slot;

use Definition\Slot\Slot;

interface SlotLoaderInterface
{
    /**
     * @param mixed $userId
     * @return Slot[]
     */
    public function getSlotsForUser($userId): array;
}
