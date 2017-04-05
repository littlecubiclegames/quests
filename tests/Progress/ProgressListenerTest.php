<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Progress;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Progress\Functions\InitProgressHandlerFunctionInterface;
use LittleCubicleGames\Quests\Progress\ProgressHandler;
use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilderInterface;
use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Tests\Quests\Mock\Progress\MockHandlerFunction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class ProgressListenerTest extends TestCase
{
    /** @var ProgressListener */
    private $listener;

    private $dispatcher;
    private $questRegistry;
    private $progressHandler;
    private $progressFunctionBuilder;

    protected function setUp()
    {
        $this->dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->questRegistry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $this->progressHandler = $this->getMockBuilder(ProgressHandler::class)->disableOriginalConstructor()->getMock();
        $this->progressFunctionBuilder = $this->getMockBuilder(ProgressFunctionBuilderInterface::class)->getMock();

        $this->listener = new ProgressListener($this->questRegistry, $this->dispatcher, $this->progressHandler, $this->progressFunctionBuilder);
    }

    public function testSubscribe()
    {
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $event = new \Symfony\Component\Workflow\Event\Event($quest, new Marking(), new Transition('test', '', ''));

        $this->mockRegisterQuest($quest);

        $this->listener->subscribeQuest($event);
    }

    public function testRegisterQuestInitProgress()
    {
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $this->mockRegisterQuest($quest, false);

        $mockHandlerFunction = $this->getMockBuilder(InitProgressHandlerFunctionInterface::class)->getMock();
        $this->progressFunctionBuilder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo('taskType'))
            ->willReturn($mockHandlerFunction);

        $this->progressHandler
            ->expects($this->once())
            ->method('initProgress')
            ->with($this->equalTo($quest), $this->equalTo(11), $this->equalTo($mockHandlerFunction));

        $this->listener->registerQuest($quest);
    }

    public function testRegisterQuest()
    {
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();

        $this->mockRegisterQuest($quest);

        $this->listener->registerQuest($quest);

        return [$this->listener, $quest, $this->dispatcher];
    }

    /**
     * @depends testRegisterQuest
     */
    public function testCallUnsubscribe($data)
    {
        list($listener, $quest, $dispatcher) = $data;
        $dispatcher
            ->expects($this->once())
            ->method('removeListener')
            ->with($this->equalTo('eventName'));

        $listener->unsubscribeQuest(new \Symfony\Component\Workflow\Event\Event($quest, new Marking(), new Transition('test', '', '')));
    }

    public function testRegisterQuestNoTasks()
    {
        $questId = 1;
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $questData = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $task = $this->getMockBuilder(TaskInterface::class)->getMock();

        $task
            ->expects($this->once())
            ->method('getTaskIdTypes')
            ->willReturn([]);
        $task
            ->expects($this->once())
            ->method('getTaskIdAttributes')
            ->willReturn([]);

        $questData
            ->expects($this->exactly(2))
            ->method('getTask')
            ->willReturn($task);

        $quest
            ->expects($this->once())
            ->method('getQuestId')
            ->willReturn($questId);

        $this->questRegistry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn($questData);

        $this->listener->registerQuest($quest);

        $this->dispatcher
            ->expects($this->never())
            ->method('addListener');
    }

    private function mockRegisterQuest($quest, $registerHandler = true)
    {
        $questId = 1;
        $questData = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();

        $task = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task
            ->expects($this->once())
            ->method('getTaskIdTypes')
            ->willReturn([$taskId = 11 => 'taskType']);
        $task
            ->expects($this->once())
            ->method('getTaskIdAttributes')
            ->willReturn([$taskId => []]);

        $questData
            ->expects($this->exactly(2))
            ->method('getTask')
            ->willReturn($task);

        $quest
            ->expects($this->any())
            ->method('getQuestId')
            ->willReturn($questId);

        $this->questRegistry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn($questData);

        if (!$registerHandler) {
            return;
        }

        $mockHandlerFunction = new MockHandlerFunction(function () {
        }, ['eventName' => 'handle']);
        $this->progressFunctionBuilder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo('taskType'))
            ->willReturn($mockHandlerFunction);

        $event = new Event();
        $this->progressHandler
            ->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($quest), $this->equalTo($taskId), $this->equalTo([$mockHandlerFunction, 'handle']), $this->equalTo($event));

        $this->dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with($this->equalTo('eventName'))
            ->willReturnCallback(function ($eventName, $listener) use ($event) {
                $listener($event);
            });
    }
}
