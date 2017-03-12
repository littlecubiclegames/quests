<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Task;

interface TaskInterface
{
    public function isFinished(array $progressMap);
    public function getTaskIdTypes();
}
