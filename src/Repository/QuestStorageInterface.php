<?php

namespace LittleCubicleGames\Quests\Repository;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestStorageInterface
{
    public function getActiveQuests($userId) : array;
    public function save(QuestInterface $quest) : QuestInterface;
}
