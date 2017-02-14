<?php

namespace LittleCubicleGames\Quests\Definition;

use LittleCubicleGames\Quests\Definition\Quest\Quest;

class Registry
{
    /** @var Quest[] */
    private $quests;

    public function __construct(array $quests)
    {
        $this->quests = $quests;
    }

    public function getQuest($id) : Quest
    {
        if (!isset($this->quests[$id])) {
            throw new \Exception(sprintf('Invalid Quest Id: %s', $id));
        }

        return $this->quests[$id];
    }
}
