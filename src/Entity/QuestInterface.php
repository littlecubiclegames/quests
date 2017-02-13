<?php

namespace LittleCubicleGames\Quests\Entity;

interface QuestInterface
{
    public function setState(string $state);
    public function getState() : string;

    public function getQuestId();

    /**
     * Method must return a map of taskId to progress
     */
    public function getProgressMap() : array;

    public function getTask($taskId) : TaskInterface;
}
