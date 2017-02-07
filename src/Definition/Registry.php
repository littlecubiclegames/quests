<?php

namespace LittleCubicleGames\Quests\Definition;

class Registry
{
    /** @var Quest[] */
    private $quests;

    public function __construct(array $quests)
    {
        $this->quests = $quests;
    }

    public function getQuest($id)
    {
        if (!isset($this->quests[$id])) {
            throw new \Exception(sprintf('Invalid Quest Id: %s', $id));
        }

        return $this->quests[$id];
    }
}
