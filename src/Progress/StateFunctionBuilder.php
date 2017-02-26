<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\FinishQuests;
use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;
use LittleCubicleGames\Quests\Progress\Functions\RejectQuests;

class StateFunctionBuilder implements ProgressFunctionBuilderInterface
{
    public function build($taskName): HandlerFunctionInterface
    {
        switch ($taskName) {
            case FinishQuests::NAME:
                return new FinishQuests();
            case RejectQuests::NAME:
                return new RejectQuests();
        }
    }
}
