<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Slot;

class Slot
{
    /** @var mixed */
    private $id;
    /** @var mixed */
    private $registryId;
    /** @var \DateTime */
    private $startDate;
    /** @var \DateTime */
    private $endDate;
    public function __construct($id, $registryId, \DateTime $startDate = null, \DateTime $endDate = null)
    {
        $this->id = $id;
        $this->registryId = $registryId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getRegistryId()
    {
        return $this->registryId;
    }
    public function isActive()
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
