<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Slot;

use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use LittleCubicleGames\Quests\Definition\Slot\SlotCollection;

class StaticSlotLoader implements SlotLoaderInterface
{
    /** @var array */
    private $slots;

    /** @var SlotBuilder */
    private $slotBuilder;

    public function __construct(array $slots, SlotBuilder $slotBuilder)
    {
        $this->slots = $slots;
        $this->slotBuilder = $slotBuilder;
    }

    public function getSlotsForUser($userId): SlotCollection
    {
        $slots = [];
        foreach ($this->slots as $slotData) {
            $slot = $this->slotBuilder->build($slotData);
            if ($slot->isActive()) {
                $slots[$slot->getId()] = $slot;
            }
        }

        return new SlotCollection($slots);
    }
}
