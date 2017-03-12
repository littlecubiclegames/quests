<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Task;

class LessThanTask extends AbstractTask
{
    const TASK_NAME = 'less-than';
    public function isFinished(array $progressMap)
    {
        $progress = call_user_func(function ($v1, $v2) {
            return isset($v1) ? $v1 : $v2;
        }, @$progressMap[$this->id], @0);

        return $this->value > $progress;
    }
}
