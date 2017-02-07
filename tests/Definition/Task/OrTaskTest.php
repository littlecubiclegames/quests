<?php

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\OrTask;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class OrTaskTest extends TestCase
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

        $task = new OrTask([$taskMock]);
        $this->assertEquals($expected, $task->isFinished([]));
    }

    public function isFinishedProvider() : array
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

        $task = new OrTask([$successTaskMock, $failTaskMock]);
        $this->assertTrue($task->isFinished([]));

        $task = new OrTask([$failTaskMock, $successTaskMock]);
        $this->assertTrue($task->isFinished([]));

        $task = new OrTask([$successTaskMock, $successTaskMock]);
        $this->assertTrue($task->isFinished([]));
    }
}
