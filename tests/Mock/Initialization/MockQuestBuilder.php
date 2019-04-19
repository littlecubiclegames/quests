<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Mock\Initialization;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestBuilderInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinition;
use LittleCubicleGames\Tests\Quests\Mock\Entity\MockQuest;

class MockQuestBuilder implements QuestBuilderInterface
{
    public function buildQuest(Quest $quest, Slot $slot, int $userId): QuestInterface
    {
        return new MockQuest($quest, $userId, QuestDefinition::STATE_AVAILABLE, $slot->getId());
    }
}
