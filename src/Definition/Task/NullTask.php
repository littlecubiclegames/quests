<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class NullTask implements TaskInterface
{
    public function isFinished(array $progressMap)
    {
        return true;
    }

    public function getTaskIdTypes()
    {
        return [];
    }

    public function getTaskIdAttributes()
    {
        return [];
    }
}
