<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;

interface QuestStorageInterface
{
    /**
     * @param mixed $userId
     * @return QuestInterface[]
     */
    public function getActiveQuests($userId);
    public function save(QuestInterface $quest);
}
