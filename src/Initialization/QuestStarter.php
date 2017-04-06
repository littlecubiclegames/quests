<?php

namespace LittleCubicleGames\Quests\Initialization;

use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\Event\Event;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
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
    public function __construct(RegistryInterface $registry, QuestBuilderInterface $questBuilder, QuestStorageInterface $questStorage, EventDispatcherInterface $dispatcher)
    {
        $this->registry = $registry;
        $this->questBuilder = $questBuilder;
        $this->questStorage = $questStorage;
        $this->dispatcher = $dispatcher;
    }

    public function triggerNext(Slot $slot, $user, QuestInterface $quest = null)
    {
        $nextQuest = $this->registry->getNextQuest($user, $slot, $quest);
        if ($nextQuest) {
            $quest = $this->questBuilder->buildQuest($nextQuest, $slot, $user);
            $this->questStorage->save($quest);
            $this->dispatcher->dispatch(Event::QUEST_ACTIVE, new Event($quest, $slot));
        }
    }
}
