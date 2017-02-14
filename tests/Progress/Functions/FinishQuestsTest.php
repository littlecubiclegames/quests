<?php

namespace LittleCubicleGames\Tests\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Entity\TaskInterface;
use LittleCubicleGames\Quests\Progress\Functions\FinishQuests;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class FinishQuestsTest extends AbstractFunctionTest
{
    protected function setUp()
    {
        $this->function = new FinishQuests();
    }

    public function testHandle()
    {
        $task = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task
            ->expects($this->once())
            ->method('getProgress')
            ->willReturn(1);

        $progress = $this->function->handle($task, new Event(null, new Marking(), new Transition('transition', '', '')));
        $this->assertSame(2, $progress);
    }
}
