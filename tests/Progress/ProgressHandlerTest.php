<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Progress;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Progress\Functions\InitProgressHandlerFunctionInterface;
use LittleCubicleGames\Quests\Progress\ProgressHandler;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use LittleCubicleGames\Tests\Quests\Mock\Progress\MockHandlerFunction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Workflow;
use Symfony\Contracts\EventDispatcher\Event;

class ProgressHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $progress = 10;
        $task = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task
            ->expects($this->once())
            ->method('updateProgress')
            ->with($this->equalTo($progress));

        $taskId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->once())
            ->method('getTask')
            ->with($this->equalTo($taskId))
            ->willReturn($task);

        $workflow = $this->getMockBuilder(Workflow::class)->disableOriginalConstructor()->getMock();
        $workflow
            ->expects($this->once())
            ->method('can')
            ->with($this->equalTo($quest), $this->equalTo(QuestDefinitionInterface::TRANSITION_COMPLETE))
            ->willReturn(false);

        $storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($quest))
            ->willReturn($quest);

        $event = new Event();
        $handlerFunction = function (TaskInterface $calledTask, Event $calledEvent) use ($task, $event, $progress): int {
            $this->assertSame($event, $calledEvent);
            $this->assertSame($task, $calledTask);

            return $progress;
        };
        $mockHandler = new MockHandlerFunction($handlerFunction);

        $handler = new ProgressHandler($workflow, $storage);
        $handler->handle($quest, $taskId, [$mockHandler, 'handle'], $event);
    }

    public function testInitProgress(): void
    {
        $progress = 10;
        $task = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task
            ->expects($this->once())
            ->method('updateProgress')
            ->with($this->equalTo($progress));

        $taskId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->once())
            ->method('getTask')
            ->with($this->equalTo($taskId))
            ->willReturn($task);

        $workflow = $this->getMockBuilder(Workflow::class)->disableOriginalConstructor()->getMock();
        $workflow
            ->expects($this->once())
            ->method('can')
            ->with($this->equalTo($quest), $this->equalTo(QuestDefinitionInterface::TRANSITION_COMPLETE))
            ->willReturn(false);

        $handlerFunction = $this->getMockBuilder(InitProgressHandlerFunctionInterface::class)->getMock();
        $handlerFunction
            ->expects($this->once())
            ->method('initProgress')
            ->with($this->equalTo($quest), $this->equalTo($task))
            ->willReturn($progress);

        $storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($quest))
            ->willReturn($quest);

        $handler = new ProgressHandler($workflow, $storage);
        $handler->initProgress($quest, $taskId, $handlerFunction);
    }
}
