<?php

namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestStorageInterface
{
    public function getActiveQuests($userId) : array;
    public function save(QuestInterface $quest) : QuestInterface;
}
