<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\Event\Event;
use LittleCubicleGames\Quests\QuestAdvancer;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QuestStarter
{
    /** @var RegistryInterface */
    private $registry;
    /** @var QuestBuilderInterface */
    private $questBuilder;
    /** @var QuestStorageInterface */
    private $questStorage;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var QuestAdvancer */
    private $questAdvancer;
    /** @var bool */
    private $autoStartNewQuests;

    public function __construct(
        RegistryInterface $registry,
        QuestBuilderInterface $questBuilder,
        QuestStorageInterface $questStorage,
        EventDispatcherInterface $dispatcher,
        QuestAdvancer $questAdvancer,
        bool $autoStartNewQuests
    ) {
        $this->registry = $registry;
        $this->questBuilder = $questBuilder;
        $this->questStorage = $questStorage;
        $this->dispatcher = $dispatcher;
        $this->questAdvancer = $questAdvancer;
        $this->autoStartNewQuests = $autoStartNewQuests;
    }

    public function triggerNext(Slot $slot, int $user, ?QuestInterface $quest): void
    {
        $nextQuest = $this->registry->getNextQuest($user, $slot, $quest);
        if (isset($nextQuest)) {
            $quest = $this->questBuilder->buildQuest($nextQuest, $slot, $user);
            $this->questStorage->save($quest);
            $this->dispatcher->dispatch(Event::QUEST_ACTIVE, new Event($quest, $slot));

            if ($this->autoStartNewQuests) {
                $this->questAdvancer->advanceQuest($quest->getId(), $user, QuestDefinitionInterface::TRANSITION_START);
            }
        }
    }
}
