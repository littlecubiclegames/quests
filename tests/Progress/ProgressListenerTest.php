<?php

namespace LittleCubicleGames\Tests\Progress;

use LittleCubicleGames\Quests\Definition\Quest;
use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Progress\ProgressHandler;
use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilderInterface;
use LittleCubicleGames\Quests\Progress\ProgressListener;
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
        $this->questRegistry = $this->getMockBuilder(Registry::class)->disableOriginalConstructor()->getMock();
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

        $questData
            ->expects($this->once())
            ->method('getTaskEventMap')
            ->willReturn([]);

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

    private function mockRegisterQuest($quest)
    {
        $questId = 1;
        $questData = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();

        $taskMap = [
            $taskId = 11 => [
                'eventName' => 'taskName',
            ]
        ];

        $questData
            ->expects($this->once())
            ->method('getTaskEventMap')
            ->willReturn($taskMap);

        $quest
            ->expects($this->any())
            ->method('getQuestId')
            ->willReturn($questId);

        $this->questRegistry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn($questData);

        $progressHandlerFunction = function () {
        };
        $this->progressFunctionBuilder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo('taskName'))
            ->willReturn($progressHandlerFunction);

        $event = new Event();
        $this->progressHandler
            ->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($quest), $this->equalTo($taskId), $this->equalTo($progressHandlerFunction), $this->equalTo($event))
            ->willReturn($progressHandlerFunction);

        $this->dispatcher
            ->expects($this->once())
            ->method('addListener')
            ->with($this->equalTo('eventName'))
            ->willReturnCallback(function ($eventName, $listener) use ($event) {
                $listener($event);
            });
    }
}
