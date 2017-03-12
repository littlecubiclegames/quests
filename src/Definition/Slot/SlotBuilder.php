<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Slot;

class SlotBuilder
{
    public function build(array $data)
    {
        $startDate = null;
        $endDate = null;
        if (isset($data['start'])) {
            $startDate = new \DateTime($data['start']);
        }
        if (isset($data['end'])) {
            $endDate = new \DateTime($data['end']);
        }

        return new Slot($data['id'], $data['registry'], $startDate, $endDate);
    }
}
