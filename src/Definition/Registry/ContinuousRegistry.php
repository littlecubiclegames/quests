<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class ContinuousRegistry extends AbstractRegistry
{
    public function getNextQuest(Slot $slot, ?QuestInterface $quest = null): ?Quest
    {
        $questIds = array_keys($this->quests);
        $newKey = $quest ? array_search($quest->getQuestId(), $questIds, true) + 1 : 0;
        if (isset($questIds[$newKey])) {
            return $this->getQuest($questIds[$newKey]);
        }

        return null;
    }
}
