<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress\Functions;

interface EventHandlerFunctionInterface extends HandlerFunctionInterface
{
    public function getEventMap(): array;
}
