<?php

namespace LittleCubicleGames\Quests\Entity;

interface TaskInterface
{
    public function updateProgress(int $progress);
    public function getProgress() : int;
}
