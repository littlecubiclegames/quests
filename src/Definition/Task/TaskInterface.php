<?php

namespace LittleCubicleGames\Quests\Definition\Task;

interface TaskInterface
{
    public function isFinished(array $progressMap);
    public function getTaskIdTypes();
    public function getTaskIdAttributes();
}
