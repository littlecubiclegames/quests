<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use LittleCubicleGames\Quests\Progress\Functions\EventHandlerFunctionInterface;
use LittleCubicleGames\Quests\Progress\Functions\InitProgressHandlerFunctionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ProgressListener implements EventSubscriberInterface
{
    /** @var RegistryInterface */
    private $questRegistry;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var ProgressHandler */
    private $questProgressHandler;
    /** @var ProgressFunctionBuilderInterface */
    private $progressFunctionBuilder;
    /** @var array[] */
    private $questListenerMap = [];

    public function __construct(RegistryInterface $questRegistry, EventDispatcherInterface $dispatcher, ProgressHandler $questProgressHandler, ProgressFunctionBuilderInterface $progressFunctionBuilder)
    {
        $this->questRegistry = $questRegistry;
        $this->dispatcher = $dispatcher;
        $this->questProgressHandler = $questProgressHandler;
        $this->progressFunctionBuilder = $progressFunctionBuilder;
    }

    public function subscribeQuest(Event $event): void
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $this->registerQuest($quest);
    }

    public function unsubscribeQuest(Event $event): void
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $listeners = $this->questListenerMap[$quest->getQuestId()] ?? [];
        foreach ($listeners as $eventName => $listener) {
            $this->dispatcher->removeListener($eventName, $listener);
        }

        unset($this->questListenerMap[$quest->getQuestId()]);
    }

    public function registerQuest(QuestInterface $quest): void
    {
        $questData = $this->questRegistry->getQuest($quest->getQuestId());

        $taskMap = $questData->getTask()->getTaskIdTypes();
        $attributesMap = $questData->getTask()->getTaskIdAttributes();
        foreach ($taskMap as $taskId => $type) {
            $handlerFunction = $this->progressFunctionBuilder->build($type, $attributesMap[$taskId]);
            if ($handlerFunction instanceof InitProgressHandlerFunctionInterface) {
                $this->questProgressHandler->initProgress($quest, $taskId, $handlerFunction);
            }

            if ($handlerFunction instanceof EventHandlerFunctionInterface) {
                foreach ($handlerFunction->getEventMap() as $eventName => $method) {
                    $callback = [$handlerFunction, $method];
                    $listener = function ($event) use ($quest, $taskId, $callback) {
                        $this->questProgressHandler->handle($quest, $taskId, $callback, $event);
                    };
                    $this->questListenerMap[$quest->getQuestId()][$eventName] = $listener;
                    $this->dispatcher->addListener($eventName, $listener);
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            sprintf('workflow.%s.enter.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::STATE_IN_PROGRESS) => 'subscribeQuest',
            sprintf('workflow.%s.leave.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::STATE_COMPLETED) => 'unsubscribeQuest',
            sprintf('workflow.%s.leave.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::STATE_REJECTED) => 'unsubscribeQuest',
        ];
    }
}
