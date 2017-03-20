<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Guard;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Guard\IsCompletedListener;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class IsCompletedListenerTest extends TestCase
{
    /**
     * @dataProvider validateProvider
     */
    public function testValidate($expected, $isFinished)
    {
        $questId = 1;
        $progressMap = [0 => 0];

        $task = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task
            ->expects($this->once())
            ->method('isFinished')
            ->with($this->equalTo($progressMap))
            ->willReturn($isFinished);

        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $quest
            ->expects($this->once())
            ->method('getQuestId')
            ->willReturn($questId);
        $quest
            ->expects($this->once())
            ->method('getProgressMap')
            ->willReturn($progressMap);

        $event = new GuardEvent($quest, new Marking(), new Transition(QuestDefinitionInterface::TRANSITION_COMPLETE, '', ''));

        $registry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $registry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn(new Quest($questId, $task));
        $listener = new IsCompletedListener($registry);
        $listener->validate($event);

        $this->assertSame($expected, $event->isBlocked());
    }

    public function validateProvider()
    {
        return [
            [true, false],
            [false, true],
        ];
    }
}
