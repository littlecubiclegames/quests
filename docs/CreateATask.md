# Create a task

Every individual task that you want to create require two components: a custom event (this can be a pre-existing event) and a handler function that can process the event and then returns the updated progress for a task.

Let's say we want to implement a task that requires a user to send X amount of messages to other users.

The first thing that we need to do is create a MessageSend event which can be as simple as in the example below.
```php
<?php declare(strict_types=1);

namespace MyProject\Message\Event;

use Symfony\Component\EventDispatcher\Event;

class MessageSend extends Event
{
    public const SEND_SUCCESS = 'message.send.success';
}
```

Now every time a user sends a message we need to send a new event via Symfony EventDispatcher like this:
```php
$dispatcher->dispatch(\MyProject\Message\Event\MessageSend::SEND_SUCCESS, new \MyProject\Message\Event\MessageSend());
```

After the event is set up the next component which is required is the handler. A handler is a event listener that returns the updated progress for as task.
In the case of a message send task every time a user sends a new message the progress for the task needs to be increased by one.
The handler also needs to implement the `getEventMap` method which defines for which events the handler is listening for. 

```php
<?php declare(strict_types=1);

namespace MyProject\Quest\Progress\Functions;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Progress\Functions\EventHandlerFunctionInterface;

class SendMessageHandler implements EventHandlerFunctionInterface
{
    public const NAME = 'send-message';

    public function handle(TaskInterface $task, \MyProject\Message\Event\MessageSend $event): int
    {
        return $task->getProgress() + 1;
    }

    public function getEventMap(): array
    {
        return [\MyProject\Message\Event\MessageSend::SEND_SUCCESS => 'handle'];
    }
}
```

As last piece of the puzzle all handlers need to be registered in a `ProgressFunctionBuilder`. Multiple handlers can be registered with the same builder. The taskName for each handler needs to match name that is being used in the quest task definition.

```php
<?php declare(strict_types=1);

namespace MyProject\Quest\Progress;

use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilderInterface;
use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;
use MyProject\Quest\Progress\Functions\MessageSendHandler;

class FunctionBuilder implements ProgressFunctionBuilderInterface
{
    public function build(string $taskName, array $attributes = []): ?HandlerFunctionInterface
    {
        switch ($taskName) {
            case MessageSendHandler::NAME:
                return new MessageSendHandler();
            default:
                return null;
        }
    }
}
```
