<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class RandomRegistry extends AbstractRegistry
{
    public function getNextQuest(Slot $slot, ?QuestInterface $quest = null): ?Quest
    {
        $key = random_int(0, count($this->quests) - 1);
        $questId = array_keys($this->quests)[$key];

        return $this->getQuest($questId);
    }
}
