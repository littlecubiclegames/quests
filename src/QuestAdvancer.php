<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\WorkflowInterface;

class QuestAdvancer
{
    /** @var QuestStorageInterface */
    private $questStorage;
    /** @var Workflow */
    private $questWorkflow;

    public function __construct(QuestStorageInterface $questStorage, WorkflowInterface $questsStateMachine)
    {
        $this->questStorage = $questStorage;
        $this->questWorkflow = $questsStateMachine;
    }

    public function startQuest(int $questId, int $userId): QuestInterface
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_START);
    }

    public function collectRewardQuest(int $questId, int $userId): QuestInterface
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD);
    }

    public function rejectQuest(int $questId, int $userId): QuestInterface
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_REJECT);
    }

    public function abortQuest(int $questId, int $userId): QuestInterface
    {
        return $this->advanceQuest($questId, $userId, QuestDefinitionInterface::TRANSITION_ABORT);
    }

    public function advanceQuest(int $questId, int $userId, string $transitionName): QuestInterface
    {
        $quest = $this->questStorage->getUserQuest($userId, $questId);
        $this->questWorkflow->apply($quest, $transitionName);
        $this->questStorage->save($quest);

        return $quest;
    }
}
