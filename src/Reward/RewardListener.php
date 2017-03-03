<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Reward;

use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class RewardListener implements EventSubscriberInterface
{
    /** @var Registry */
    private $questRegistry;

    /** @var Provider */
    private $rewardCollectorProvider;

    public function __construct(Registry $questRegistry, Provider $rewardCollectorProvider)
    {
        $this->questRegistry = $questRegistry;
        $this->rewardCollectorProvider = $rewardCollectorProvider;
    }

    public function collect(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $questDefinition = $this->questRegistry->getQuest($quest->getQuestId());
        if (!$questDefinition->hasReward()) {
            return;
        }

        $reward = $questDefinition->getReward();
        $reward->collect($this->rewardCollectorProvider, $quest);
    }

    public static function getSubscribedEvents()
    {
        return [
            sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD) => 'collect',
        ];
    }
}
