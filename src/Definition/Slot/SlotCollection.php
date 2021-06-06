<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Slot;

class SlotCollection
{
    /** @var Slot[] */
    private $slots;
    /** @var bool[] */
    private $usedSlots;

    public function __construct(array $slots)
    {
        $this->slots = $slots;
        $this->usedSlots = [];
    }

    public function isSlotAvailable(string $slotId): bool
    {
        return isset($this->slots[$slotId]) && $this->slots[$slotId]->isActive() && !isset($this->usedSlots[$slotId]);
    }

    public function getSlot(string $slotId): ?Slot
    {
        if ($this->isSlotAvailable($slotId)) {
            return $this->slots[$slotId];
        }

        return null;
    }

    public function markSlotAsUsed(string $slotId): void
    {
        $this->usedSlots[$slotId] = true;
    }

    public function getUnusedSlots(): array
    {
        return array_filter(array_diff_key($this->slots, $this->usedSlots), function (Slot $slot): bool {
            return $slot->isActive();
        });
    }
}
