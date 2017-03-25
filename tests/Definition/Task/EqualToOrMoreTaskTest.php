<?php

namespace Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\EqualToOrMoreTask;
use PHPUnit\Framework\TestCase;

class EqualToOrMoreTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished($value, $taskId, array $progressMap, $expected)
    {
        $task = new EqualToOrMoreTask($taskId, 'type', $value);
        $this->assertSame($expected, $task->isFinished($progressMap));
    }

    public function isFinishedProvider()
    {
        return [
            [0, 0, [0 => 0], true],
            [1, 0, [0 => 0], false],
            [0, 0, [0 => 1], true],
            [0, 0, [0 => -1], false],
            [0, 1, [0 => 0, 1 => 1], true],
        ];
    }

    public function testGetTaskIdTypes()
    {
        $task = new EqualToOrMoreTask(1, 'type', 10);
        $this->assertEquals([1 => 'type'], $task->getTaskIdTypes());
    }
}
