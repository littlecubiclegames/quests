<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Workflow;

interface QuestDefinitionInterface
{
    const WORKFLOW_NAME = 'quests';
    const STATE_AVAILABLE = 'available';
    const STATE_IN_PROGRESS = 'in_progress';
    const STATE_COMPLETED = 'completed';
    const STATE_FINISHED = 'finished';
    const STATE_REJECTED = 'rejected';
    const TRANSITION_START = 'start';
    const TRANSITION_COMPLETE = 'complete';
    const TRANSITION_COLLECT_REWARD = 'collect_reward';
    const TRANSITION_REJECT = 'reject';
    const TRANSITION_ABORT = 'abort';
    public function build();
}
