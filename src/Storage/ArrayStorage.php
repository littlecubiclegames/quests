<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;

class ArrayStorage implements QuestStorageInterface
{
    /** @var QuestInterface[] */
    private $quests = [];

    /**
     * @param QuestInterface[] $quests
     */
    public function __construct(array $quests = [])
    {
        foreach ($quests as $quest) {
            $this->quests[$quest->getId()] = $quest;
        }
    }

    public function getActiveQuests(int $userId): array
    {
        return array_values(array_filter($this->quests, function (QuestInterface $quest): bool {
            return !in_array($quest->getState(), [QuestDefinitionInterface::STATE_COMPLETED, QuestDefinitionInterface::STATE_REJECTED], true);
        }));
    }

    public function save(QuestInterface $quest): QuestInterface
    {
        $this->quests[$quest->getId()] = $quest;

        return $quest;
    }

    public function getUserQuest(int $userId, int $questId): QuestInterface
    {
        if (!isset($this->quests[$questId])) {
            throw new QuestNotFoundException();
        }

        return $this->quests[$questId];
    }
}
