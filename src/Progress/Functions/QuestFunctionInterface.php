<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use Symfony\Component\Workflow\Event\Event;

interface QuestFunctionInterface extends EventHandlerFunctionInterface
{
    public function handle(TaskInterface $task, Event $event): int;
}
