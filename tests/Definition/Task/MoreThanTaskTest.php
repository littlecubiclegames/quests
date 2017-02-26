<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\MoreThanTask;
use PHPUnit\Framework\TestCase;

class MoreThanTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished(int $value, int $taskId, array $progressMap, bool $expected)
    {
        $task = new MoreThanTask($taskId, 'type', $value);
        $this->assertSame($expected, $task->isFinished($progressMap));
    }

    public function isFinishedProvider() : array
    {
        return [
            [0, 0, [0 => 1], true],
            [0, 0, [0 => 0], false],
            [0, 0, [0 => -1], false],
            [0, 1, [0 => 99, 1 => 0], false],
        ];
    }

    public function testGetTaskIdTypes()
    {
        $task = new MoreThanTask(1, 'type', 10);
        $this->assertEquals([1 => 'type'], $task->getTaskIdTypes());
    }
}
