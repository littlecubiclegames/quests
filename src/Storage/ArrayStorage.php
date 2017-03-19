<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Storage;

use LittleCubicleGames\Quests\Entity\QuestInterface;

class ArrayStorage implements QuestStorageInterface
{
    /** @var QuestInterface[] */
    private $quests = array();
    public function __construct(array $quests = array())
    {
        foreach ($quests as $quest) {
            $this->quests[$quest->getQuestId()] = $quest;
        }
    }
    public function getActiveQuests($userId)
    {
        return array_values($this->quests);
    }
    public function save(QuestInterface $quest)
    {
        $this->quests[$quest->getQuestId()] = $quest;

        return $quest;
    }
}
