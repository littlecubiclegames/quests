<?php

namespace LittleCubicleGames\Tests\Quests\Initialization;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestInitializer;
use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Repository\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use PHPUnit\Framework\TestCase;

class QuestInitializerTest extends TestCase
{
    public function testInitialize()
    {
        $userId = 1;

        $storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $progressListener = $this->getMockBuilder(ProgressListener::class)->disableOriginalConstructor()->getMock();

        $quest1 = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest1
            ->expects($this->once())
            ->method('getState')
            ->willReturn(QuestDefinitionInterface::STATE_AVAILABLE);
        $quest2 = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest2
            ->expects($this->once())
            ->method('getState')
            ->willReturn(QuestDefinitionInterface::STATE_IN_PROGRESS);

        $storage
            ->expects($this->once())
            ->method('getActiveQuests')
            ->with($this->equalTo($userId))
            ->willReturn([
                $quest1,
                $quest2,
            ]);

        $progressListener
            ->expects($this->once())
            ->method('registerQuest')
            ->with($this->equalTo($quest2));

        $initializer = new QuestInitializer($storage, $progressListener);
        $initializer->initialize($userId);
    }
}
