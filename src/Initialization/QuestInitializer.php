<?php

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Repository\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;

class QuestInitializer
{
    /** @var QuestStorageInterface */
    private $questStorage;

    /** @var ProgressListener */
    private $questProgressListener;

    public function __construct(QuestStorageInterface $questStorage, ProgressListener $questProgressListener)
    {
        $this->questStorage = $questStorage;
        $this->questProgressListener = $questProgressListener;
    }

    public function initialize($userId)
    {
        $quests = $this->questStorage->getActiveQuests($userId);
        foreach ($quests as $quest) {
            if ($quest->getState() === QuestDefinitionInterface::STATE_IN_PROGRESS) {
                $this->questProgressListener->registerQuest($quest);
            }
        }
    }
}
