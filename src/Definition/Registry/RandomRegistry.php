<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class RandomRegistry extends AbstractRegistry
{
    public function getNextQuest($user, Slot $slot, ?QuestInterface $quest = null): ?Quest
    {
        return $this->pickAndValidateQuest(array_keys($this->quests), $user, $slot);
    }

    private function pickAndValidateQuest(array $questIds, $user, Slot $slot): ?Quest
    {
        if (empty($questIds)) {
            return null;
        }

        $key = random_int(0, count($questIds) - 1);
        $questId = $questIds[$key];
        unset($questIds[$key]);
        $newQuest = $this->getQuest($questId);

        if ($this->triggerValidator->canTrigger($newQuest, $slot, $user)) {
            return $newQuest;
        }

        return $this->pickAndValidateQuest(array_values($questIds), $user, $slot);
    }
}
