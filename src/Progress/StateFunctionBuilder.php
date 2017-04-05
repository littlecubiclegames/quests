<?php

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\FinishQuests;
use LittleCubicleGames\Quests\Progress\Functions\RejectQuests;

class StateFunctionBuilder implements ProgressFunctionBuilderInterface
{
    public function build($taskName, array $attributes = [])
    {
        switch ($taskName) {
            case FinishQuests::NAME:
                return new FinishQuests();
            case RejectQuests::NAME:
                return new RejectQuests();
            default:
                return null;
        }
    }
}
