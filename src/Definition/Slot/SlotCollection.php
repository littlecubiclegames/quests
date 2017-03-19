<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
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
        $this->usedSlots = array();
    }
    public function isSlotAvailable($slotId)
    {
        $ret158cefc19e89b0 = isset($this->slots[$slotId]) && $this->slots[$slotId]->isActive() && !isset($this->usedSlots[$slotId]);
        if (!is_bool($ret158cefc19e89b0)) {
            throw new \InvalidArgumentException('Argument returned must be of the type bool, ' . gettype($ret158cefc19e89b0) . ' given');
        }

        return $ret158cefc19e89b0;
    }
    public function getSlot($slotId)
    {
        if ($this->isSlotAvailable($slotId)) {
            return $this->slots[$slotId];
        }

        return null;
    }
    public function markSlotAsUsed($slotId)
    {
        $this->usedSlots[$slotId] = true;
    }
    public function getUnusedSlots()
    {
        $ret158cefc19e98d0 = array_filter(array_diff_key($this->slots, $this->usedSlots), function (Slot $slot) {
            return $slot->isActive();
        });
        if (!is_array($ret158cefc19e98d0)) {
            throw new \InvalidArgumentException('Argument returned must be of the type array, ' . gettype($ret158cefc19e98d0) . ' given');
        }

        return $ret158cefc19e98d0;
    }
}
