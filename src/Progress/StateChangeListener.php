<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class StateChangeListener implements EventSubscriberInterface
{
    /** @var QuestStorageInterface */
    private $questStorage;

    public function __construct(QuestStorageInterface $questStorage)
    {
        $this->questStorage = $questStorage;
    }

    public function handle(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();

        $this->questStorage->save($quest);
    }

    public static function getSubscribedEvents()
    {
        return [
            sprintf('workflow.%s.transition', QuestDefinitionInterface::WORKFLOW_NAME) => 'handle',
        ];
    }
}
