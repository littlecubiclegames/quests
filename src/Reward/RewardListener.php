<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Reward;

use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class RewardListener implements EventSubscriberInterface
{
    /** @var RegistryInterface */
    private $questRegistry;
    /** @var Provider */
    private $rewardCollectorProvider;

    public function __construct(RegistryInterface $questRegistry, Provider $rewardCollectorProvider)
    {
        $this->questRegistry = $questRegistry;
        $this->rewardCollectorProvider = $rewardCollectorProvider;
    }

    public function collect(Event $event): void
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $questDefinition = $this->questRegistry->getQuest($quest->getQuestId());
        $reward = $questDefinition->getReward();
        if (!isset($reward)) {
            return;
        }

        $reward->collect($this->rewardCollectorProvider, $quest);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            sprintf('workflow.%s.enter.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::STATE_FINISHED) => 'collect',
        ];
    }
}
