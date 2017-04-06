<?php

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

interface RegistryInterface
{
    public function getQuest($id);
    public function getNextQuest($user, Slot $slot, QuestInterface $quest = null);
}
