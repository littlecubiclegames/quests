<?php

namespace LittleCubicleGames\Quests\Log;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestLoggerInterface
{
    public function log(QuestInterface $quest, string $previousState, string $newState);
}
