<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Entity;

interface TaskInterface
{
    public function updateProgress($progress);
    public function getProgress();
}
