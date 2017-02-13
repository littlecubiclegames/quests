<?php

namespace LittleCubicleGames\Quests\Repository;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestStorageInterface
{
    public function save(QuestInterface $quest) : QuestInterface;
}
