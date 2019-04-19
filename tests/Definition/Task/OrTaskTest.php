<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\OrTask;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class OrTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished(bool $value, bool $expected): void
    {
        $taskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $taskMock
            ->method('isFinished')
            ->willReturn($value);

        $task = new OrTask([$taskMock]);
        $this->assertSame($expected, $task->isFinished([]));
    }

    public function isFinishedProvider(): array
    {
        return [
            [false, false],
            [true, true],
        ];
    }

    public function testIsFinishedMultiple(): void
    {
        $successTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $successTaskMock
            ->method('isFinished')
            ->willReturn(true);

        $failTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $failTaskMock
            ->method('isFinished')
            ->willReturn(false);

        $task = new OrTask([$successTaskMock, $failTaskMock]);
        $this->assertTrue($task->isFinished([]));

        $task = new OrTask([$failTaskMock, $successTaskMock]);
        $this->assertTrue($task->isFinished([]));

        $task = new OrTask([$successTaskMock, $successTaskMock]);
        $this->assertTrue($task->isFinished([]));
    }

    public function testGetTaskIdTypesEmpty(): void
    {
        $task = new OrTask([]);
        $this->assertSame([], $task->getTaskIdTypes());
    }

    public function testGetTaskIdTypes(): void
    {
        $task1Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task1Mock
            ->method('getTaskIdTypes')
            ->willReturn([1 => 'type1']);

        $task2Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task2Mock
            ->method('getTaskIdTypes')
            ->willReturn([2 => 'type2']);

        $task = new OrTask([$task1Mock, $task2Mock]);
        $this->assertEquals([1 => 'type1', 2 => 'type2'], $task->getTaskIdTypes());

        $task = new OrTask([$task2Mock, $task1Mock]);
        $this->assertEquals([2 => 'type2', 1 => 'type1'], $task->getTaskIdTypes());
    }
}
