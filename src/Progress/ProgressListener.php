<?php

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ProgressListener implements EventSubscriberInterface
{
    /** @var Registry */
    private $questRegistry;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var ProgressHandler */
    private $questProgressHandler;

    /** @var ProgressHandlerBuilderInterface */
    private $progressHandlerBuilder;

    /** @var array[] */
    private $questListenerMap = [];

    public function __construct(Registry $questRegistry, EventDispatcherInterface $dispatcher, ProgressHandler $questProgressHandler, ProgressHandlerBuilderInterface $progressHandlerBuilder)
    {
        $this->questRegistry = $questRegistry;
        $this->dispatcher = $dispatcher;
        $this->questProgressHandler = $questProgressHandler;
        $this->progressHandlerBuilder = $progressHandlerBuilder;
    }

    public function subscribeQuest(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $this->registerQuest($quest);
    }

    public function unsubscribeQuest(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $listeners = $this->questListenerMap[$quest->getQuestId()] ?? [];
        foreach ($listeners as $eventName => $listener) {
            $this->dispatcher->removeListener($eventName, $listener);
        }

        unset($this->questListenerMap[$quest->getQuestId()]);
    }

    public function registerQuest(QuestInterface $quest)
    {
        $questData = $this->questRegistry->getQuest($quest->getQuestId());

        $taskMap = $questData->getTaskEventMap();
        foreach ($taskMap as $taskId => $tasks) {
            foreach ($tasks as $eventName => $taskName) {
                $listener = function (\Symfony\Component\EventDispatcher\Event $event) use ($quest, $taskId, $taskName) {
                    $handlerFunction = $this->progressHandlerBuilder->build($taskName);
                    $this->questProgressHandler->handle($quest, $taskId, $handlerFunction, $event);
                };
                $this->questListenerMap[$quest->getQuestId()][$eventName] = $listener;
                $this->dispatcher->addListener($eventName, $listener);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_START) => 'subscribeQuest',
            sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD) => 'unsubscribeQuest',
            sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_ABORT) => 'unsubscribeQuest',
            sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_REJECT) => 'unsubscribeQuest',
        ];
    }
}
