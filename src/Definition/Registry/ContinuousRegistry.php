<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class ContinuousRegistry extends AbstractRegistry
{
    public function getNextQuest(int $user, Slot $slot, ?QuestInterface $quest = null): ?Quest
    {
        if (count($this->quests) === 0) {
            return null;
        }

        $questId = isset($quest) ? $quest->getQuestId() : array_keys($this->quests)[0];

        return $this->pickAndValidateQuest($questId, $user, $slot);
    }

    private function pickAndValidateQuest(int $questId, int $user, Slot $slot): ?Quest
    {
        $newQuest = $this->getQuest($questId);
        if ($this->triggerValidator->canTrigger($newQuest, $slot, $user)) {
            return $newQuest;
        }

        $questIds = array_keys($this->quests);
        $newKey = array_search($questId, $questIds, true) + 1;

        if (isset($questIds[$newKey])) {
            return $this->pickAndValidateQuest($questIds[$newKey], $user, $slot);
        }

        return null;
    }
}
