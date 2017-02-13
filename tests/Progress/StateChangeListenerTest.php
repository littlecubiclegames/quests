<?php

namespace LittleCubicleGames\Tests\Progress;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Progress\StateChangeListener;
use LittleCubicleGames\Quests\Repository\QuestStorageInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class StateChangeListenerTest extends TestCase
{
    public function testHandle()
    {
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $event = new Event($quest, new Marking(), new Transition('transition', '', ''));

        $storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $storage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($quest));

        $listener = new StateChangeListener($storage);
        $listener->handle($event);
    }
}
