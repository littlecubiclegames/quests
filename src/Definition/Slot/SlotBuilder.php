<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Slot;

/**
 * @phpstan-type SlotData array{id: string, start?: ?string, end?: ?string, registry: string}
 */
class SlotBuilder
{
    /**
     * @param SlotData $data
     */
    public function build(array $data): Slot
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
