<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\FinishQuests;
use LittleCubicleGames\Quests\Progress\Functions\RejectQuests;

class StateFunctionBuilder implements ProgressFunctionBuilderInterface
{
    public function build($taskName)
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
