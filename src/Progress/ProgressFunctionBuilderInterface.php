<?php

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;

interface ProgressFunctionBuilderInterface
{
    public function build($taskName) : HandlerFunctionInterface;
}
