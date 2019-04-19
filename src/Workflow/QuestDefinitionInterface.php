<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Workflow;

use Symfony\Component\Workflow\Definition;

interface QuestDefinitionInterface
{
    public const WORKFLOW_NAME = 'quests';

    public const STATE_AVAILABLE = 'available';
    public const STATE_IN_PROGRESS = 'in_progress';
    public const STATE_COMPLETED = 'completed';
    public const STATE_FINISHED = 'finished';
    public const STATE_REJECTED = 'rejected';

    public const STATES = [
        self::STATE_AVAILABLE,
        self::STATE_IN_PROGRESS,
        self::STATE_COMPLETED,
        self::STATE_FINISHED,
        self::STATE_REJECTED,
    ];

    public const TRANSITION_START = 'start';
    public const TRANSITION_COMPLETE = 'complete';
    public const TRANSITION_COLLECT_REWARD = 'collect_reward';
    public const TRANSITION_REJECT = 'reject';
    public const TRANSITION_ABORT = 'abort';

    public function build(): Definition;
}
