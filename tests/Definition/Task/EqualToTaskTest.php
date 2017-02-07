<?php

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\EqualToTask;
use PHPUnit\Framework\TestCase;

class EqualToTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished(int $value, int $taskId, array $progressMap, bool $expected)
    {
        $task = new EqualToTask($taskId, $value);
        $this->assertEquals($expected, $task->isFinished($progressMap));
    }

    public function isFinishedProvider() : array
    {
        return [
            [0, 0, [0 => 0], true],
            [0, 0, [0 => '0'], false],
            [0, 0, [0 => 1], false],
            [0, 0, [0 => -1], false],
            [0, 1, [0 => 0, 1 => 1], false],
        ];
    }
}
