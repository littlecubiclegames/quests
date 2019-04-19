<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestStorageInterface
{
    /**
     * @return QuestInterface[]
     */
    public function getActiveQuests(int $userId): array;
    public function save(QuestInterface $quest): QuestInterface;
    public function getUserQuest(int $userId, int $questId): QuestInterface;
}
