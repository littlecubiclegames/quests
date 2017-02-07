<?php

namespace LittleCubicleGames\Tests\Quests\Log;

use LittleCubicleGames\Quests\Entity\Quest;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Log\QuestLoggerInterface;
use LittleCubicleGames\Quests\Log\QuestLogListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class QuestLogListenerTest extends TestCase
{
    public function testLogChange()
    {
        $previousState = 'previous';
        $newState = 'new';

        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->once())
            ->method('getState')
            ->willReturn($previousState);

        $logger = $this->getMockBuilder(QuestLoggerInterface::class)->getMock();
        $logger
            ->expects($this->once())
            ->method('log')
            ->with($this->equalTo($quest), $this->equalTo($previousState), $this->equalTo($newState));

        $listener = new QuestLogListener([$logger]);
        $listener->logChange(new Event($quest, new Marking(), new Transition($newState, $previousState, '')));
    }
}
