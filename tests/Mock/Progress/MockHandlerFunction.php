<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Progress;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Progress\Functions\EventHandlerFunctionInterface;
use Symfony\Component\EventDispatcher\Event;

class MockHandlerFunction implements EventHandlerFunctionInterface
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
