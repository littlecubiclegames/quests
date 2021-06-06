<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Mock\Progress;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Progress\Functions\EventHandlerFunctionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class MockHandlerFunction implements EventHandlerFunctionInterface
{
    /** @var callable */
    private $handlerFunction;
    /** @var array */
    private $events;

    public function __construct(callable $handlerFunction, array $events = [])
    {
        $this->handlerFunction = $handlerFunction;
        $this->events = $events;
    }

    public function handle(TaskInterface $calledTask, Event $event): int
    {
        $handle = $this->handlerFunction;

        return $handle($calledTask, $event);
    }

    public function getEventMap(): array
    {
        return $this->events;
    }
}
