<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestBuilderInterface
{
    public function buildQuest(Quest $quest, Slot $slot, int $userId): QuestInterface;
}
