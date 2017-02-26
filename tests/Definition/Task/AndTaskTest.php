<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\AndTask;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class AndTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished(bool $value, bool $expected)
    {
        $taskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $taskMock
            ->method('isFinished')
            ->willReturn($value);

        $task = new AndTask([$taskMock]);
        $this->assertSame($expected, $task->isFinished([]));
    }

    public function isFinishedProvider(): array
    {
        return [
            [false, false],
            [true, true],
        ];
    }

    public function testIsFinishedMultiple()
    {
        $successTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $successTaskMock
            ->method('isFinished')
            ->willReturn(true);

        $failTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $failTaskMock
            ->method('isFinished')
            ->willReturn(false);

        $task = new AndTask([$successTaskMock, $failTaskMock]);
        $this->assertFalse($task->isFinished([]));

        $task = new AndTask([$failTaskMock, $successTaskMock]);
        $this->assertFalse($task->isFinished([]));

        $task = new AndTask([$successTaskMock, $successTaskMock]);
        $this->assertTrue($task->isFinished([]));
    }

    public function testGetTaskIdTypesEmpty()
    {
        $task = new AndTask([]);
        $this->assertSame([], $task->getTaskIdTypes());
    }

    public function testGetTaskIdTypes()
    {
        $task1Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task1Mock
            ->method('getTaskIdTypes')
            ->willReturn([1 => 'type1']);

        $task2Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task2Mock
            ->method('getTaskIdTypes')
            ->willReturn([2 => 'type2']);

        $task = new AndTask([$task1Mock, $task2Mock]);
        $this->assertEquals([1 => 'type1', 2 => 'type2'], $task->getTaskIdTypes());

        $task = new AndTask([$task2Mock, $task1Mock]);
        $this->assertEquals([2 => 'type2', 1 => 'type1'], $task->getTaskIdTypes());
    }
}
