<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Workflow;

use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Transition;

class QuestDefinition implements QuestDefinitionInterface
{
    public function build()
    {
        $builder = new DefinitionBuilder();
        $builder->addPlaces([self::STATE_AVAILABLE, self::STATE_IN_PROGRESS, self::STATE_COMPLETED, self::STATE_FINISHED, self::STATE_REJECTED]);
        $builder->setInitialPlace(self::STATE_AVAILABLE);
        $builder->addTransition(new Transition(self::TRANSITION_START, self::STATE_AVAILABLE, self::STATE_IN_PROGRESS));
        $builder->addTransition(new Transition(self::TRANSITION_COMPLETE, self::STATE_IN_PROGRESS, self::STATE_COMPLETED));
        $builder->addTransition(new Transition(self::TRANSITION_COLLECT_REWARD, self::STATE_COMPLETED, self::STATE_FINISHED));
        $builder->addTransition(new Transition(self::TRANSITION_REJECT, self::STATE_AVAILABLE, self::STATE_REJECTED));
        $builder->addTransition(new Transition(self::TRANSITION_ABORT, self::STATE_IN_PROGRESS, self::STATE_REJECTED));

        return $builder->build();
    }
}
