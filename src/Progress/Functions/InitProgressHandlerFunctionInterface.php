<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Entity\TaskInterface;

interface InitProgressHandlerFunctionInterface extends HandlerFunctionInterface
{
    public function initProgress(QuestInterface $quest, TaskInterface $task): int;
}
