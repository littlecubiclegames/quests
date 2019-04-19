<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Slot;

class Slot
{
    /** @var string */
    private $id;
    /** @var string */
    private $registryId;
    /** @var \DateTime */
    private $startDate;
    /** @var \DateTime */
    private $endDate;

    public function __construct(string $id, string $registryId, ?\DateTime $startDate = null, ?\DateTime $endDate = null)
    {
        $this->id = $id;
        $this->registryId = $registryId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRegistryId(): string
    {
        return $this->registryId;
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
