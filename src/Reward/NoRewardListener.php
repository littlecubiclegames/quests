<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Reward;

use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Workflow;

class NoRewardListener implements EventSubscriberInterface
{
    /** @var Registry */
    private $questRegistry;
    /** @var Workflow */
    private $worfkflow;
    public function __construct(Registry $questRegistry, Workflow $worfkflow)
    {
        $this->questRegistry = $questRegistry;
        $this->worfkflow = $worfkflow;
    }
    public function validate(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();
        $questDefinition = $this->questRegistry->getQuest($quest->getQuestId());
        if (!$questDefinition->hasReward() && $this->worfkflow->can($quest, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD)) {
            $this->worfkflow->apply($quest, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD);
        }
    }
    public static function getSubscribedEvents()
    {
        return array(sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_COMPLETE) => 'validate');
    }
}
