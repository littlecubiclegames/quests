<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Entity;

interface QuestInterface
{
    public function setState($state);
    public function getState();
    public function getQuestId();
    public function getUser();
    public function getSlotId();
    /**
     * Method must return a map of taskId to progress
     */
    public function getProgressMap();
    public function getTask($taskId);
}
