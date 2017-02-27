<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Slot;

class Slot
{
    /** @var mixed */
    private $id;

    /** @var \DateTime */
    private $startDate;

    /** @var \DateTime */
    private $endDate;

    public function __construct($id, ?\DateTime $startDate = null, ?\DateTime $endDate = null)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        if ($this->startDate === null && $this->endDate === null) {
            return true;
        }

        $now = new \DateTime();
        if ($this->startDate && $this->startDate > $now) {
            return false;
        }

        if ($this->endDate && $this->endDate < $now) {
            return false;
        }

        return true;
    }
}
