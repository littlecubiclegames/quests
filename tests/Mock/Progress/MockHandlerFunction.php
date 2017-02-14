<?php

namespace LittleCubicleGames\Tests\Quests\Mock\Progress;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Progress\HandlerFunctionInterface;
use Symfony\Component\EventDispatcher\Event;

class MockHandlerFunction implements HandlerFunctionInterface
{
    /** @var callable */
    private $handlerFunction;

    /** @var array */
    private $events;

    public function __construct(callable $handlerFunction, $events = [])
    {
        $this->handlerFunction = $handlerFunction;
        $this->events = $events;
    }

    public function handle(TaskInterface $calledTask, Event $calledEvent)
    {
        $handle = $this->handlerFunction;
        return $handle($calledTask, $calledEvent);
    }

    public function getEventMap(): array
    {
        return $this->events;
    }
}
