<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestStorageInterface
{
    /**
     * @param mixed $userId
     * @return QuestInterface[]
     */
    public function getActiveQuests($userId): array;
    public function save(QuestInterface $quest): QuestInterface;
    public function getUserQuest($userId, $questId): QuestInterface;
}
