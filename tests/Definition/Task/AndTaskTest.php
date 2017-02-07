<?php

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

        $task = new AndTask([$successTaskMock, $failTaskMock]);
        $this->assertFalse($task->isFinished([]));

        $task = new AndTask([$failTaskMock, $successTaskMock]);
        $this->assertFalse($task->isFinished([]));

        $task = new AndTask([$successTaskMock, $successTaskMock]);
        $this->assertTrue($task->isFinished([]));
    }
}
