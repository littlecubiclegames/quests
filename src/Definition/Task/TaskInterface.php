<?php

namespace LittleCubicleGames\Quests\Definition\Task;

interface TaskInterface
{
    public function isFinished(array $progressMap) : bool;

    public function getTaskIdTypes() : array;
}
