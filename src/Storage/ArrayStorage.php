<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;

class ArrayStorage implements QuestStorageInterface
{
    /** @var QuestInterface[] */
    private $quests = [];

    public function __construct(array $quests = [])
    {
        foreach ($quests as $quest) {
            $this->quests[$quest->getQuestId()] = $quest;
        }
    }

    public function getActiveQuests($userId): array
    {
        return array_values($this->quests);
    }

    public function save(QuestInterface $quest): QuestInterface
    {
        $this->quests[$quest->getQuestId()] = $quest;

        return $quest;
    }
}
