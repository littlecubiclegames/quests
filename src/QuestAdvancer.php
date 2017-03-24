<?php

namespace LittleCubicleGames\Quests;

use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\Workflow\Workflow;

class QuestAdvancer
{
    /** @var QuestStorageInterface */
    private $questStorage;
    /** @var Workflow */
    private $questWorkflow;

    public function __construct(QuestStorageInterface $questStorage, Workflow $questWorkflow)
    {
        $this->questStorage = $questStorage;
        $this->questWorkflow = $questWorkflow;
    }

    public function startQuest($questId, $userId)
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_START);
    }

    public function collectRewardQuest($questId, $userId)
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD);
    }

    public function rejectQuest($questId, $userId)
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_REJECT);
    }

    public function abortQuest($questId, $userId)
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_ABORT);
    }

    public function advanceQuest($questId, $userId, $transitionName)
    {
        $quest = $this->questStorage->getUserQuest($questId, $userId);
        $this->questWorkflow->apply($quest, $transitionName);
        $this->questStorage->save($quest);

        return $quest;
    }
}
